<?php

namespace App\Controller;

use App\Entity\Resume;
use App\Form\ResumeType;
use App\Repository\ResumeRepository;
use App\Service\ResumeFileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/resume')]
class ResumeController extends AbstractController
{
    #[Route('/', name: 'app_resume_index', methods: ['GET'])]
    public function index(ResumeRepository $resumeRepository): Response
    {
        return $this->render('resume/index.html.twig', [
            'resumes' => $resumeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_resume_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $em, ResumeFileUploader $fileUploader,
    ): Response {
        $resume = new Resume();
        $form = $this->createForm(ResumeType::class, $resume);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $resume->setCreatedAt(new \DateTime());

            $file = $form->get('file')->getData();

            if ($file) {
                try {
                    $newFilename = $fileUploader->uploadFile($file);

                    $resume->setFile($newFilename);
                } catch (Exception $e) {
                    throw new Exception('Could not move the file to the target directory.');
                }
            }

            $em->persist($resume);
            $em->flush();

            return $this->redirectToRoute('app_resume_index');
        }

        return $this->render('resume/new.html.twig', [
            'resume' => $resume,
            'form'   => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_resume_show', methods: ['GET'])]
    public function show(Resume $resume): Response
    {
        return $this->render('resume/show.html.twig', [
            'resume' => $resume,
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/{id}/edit', name: 'app_resume_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Resume $resume,
        EntityManagerInterface $em,
        ResumeFileUploader $fileUploader,
    ): Response {
        $form = $this->createForm(ResumeType::class, $resume);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            $oldFilename = $resume->getFile();

            if ($file) {
                try {
                    $newFilename = $fileUploader->uploadFile($file);

                    $resume->setFile($newFilename);
                } catch (Exception $e) {
                    $this->addFlash('danger', 'Failed to upload new file: ' . $e->getMessage());

                    return $this->redirectToRoute('app_resume_edit', ['id' => $resume->getId()]);
                }

                if ($oldFilename) {
                    $fileUploader->deleteFile($oldFilename);
                }
            }

            $resume->setUpdatedAt(new \DateTime());

            $em->flush();

            $this->addFlash('success', 'Resume updated successfully');

            return $this->redirectToRoute('app_resume_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('resume/edit.html.twig', [
            'resume' => $resume,
            'form'   => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_resume_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Resume $resume,
        EntityManagerInterface $em,
        ResumeFileUploader $fileUploader,
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $resume->getId(), $request->request->get('_token'))) {
            if ($resume->getFile()) {
                $fileUploader->deleteFile($resume->getFile());
            }

            $em->remove($resume);
            $em->flush();
        }

        return $this->redirectToRoute('app_resume_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/download/{filename}', name: 'download_resume')]
    public function download(string $filename): Response
    {
        $filePath = $this->getParameter('resumes_directory') . '/' . $filename;

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('The file does not exist');
        }

        $response = new BinaryFileResponse($filePath);

        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);

        return $response;
    }
}
