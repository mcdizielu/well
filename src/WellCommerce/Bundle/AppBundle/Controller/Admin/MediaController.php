<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\AppBundle\Controller\Admin;

use Carbon\Carbon;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\AppBundle\Exception\InvalidMediaException;
use WellCommerce\Bundle\AppBundle\Service\Media\Uploader\MediaUploaderInterface;
use WellCommerce\Bundle\CoreBundle\Controller\Admin\AbstractAdminController;

/**
 * Class MediaController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class MediaController extends AbstractAdminController
{
    /**
     * @var \WellCommerce\Bundle\AppBundle\Manager\MediaManager
     */
    protected $manager;
    
    public function addAction(Request $request): Response
    {
        if (false === $request->files->has('file')) {
            return $this->redirectToAction('index');
        }
        
        $file   = $request->files->get('file');
        $helper = $this->getImageHelper();
        
        try {
            $media     = $this->getUploader()->upload($file, 'images');
            $thumbnail = $helper->getImage($media->getPath(), 'medium');
            
            $response = [
                'sId'        => $media->getId(),
                'sThumb'     => $thumbnail,
                'sFilename'  => $media->getName(),
                'sExtension' => $media->getExtension(),
                'sFileType'  => $media->getMime(),
            ];
        } catch (InvalidMediaException $e) {
            $response = [
                'sError'   => $this->trans('uploader.error'),
                'sMessage' => $this->trans($e->getMessage()),
            ];
        }
        
        return $this->jsonResponse($response);
    }
    
    public function indexLocalAction(Request $request): JsonResponse
    {
        $rootPath   = trim($request->get('rootPath'), '/');
        $path       = trim($request->get('path'), '/');
        $extensions = $request->get('types');
        $finder     = $this->createFinder($path, $extensions);
        $files      = [];
        $inRoot     = $rootPath === $path;
        
        if (!$inRoot) {
            $currentPath = explode('/', $path);
            array_pop($currentPath);
            
            $files[] = [
                'dir'   => true,
                'name'  => '..',
                'path'  => implode('/', $currentPath),
                'size'  => '',
                'mtime' => '',
            ];
        }
        
        foreach ($finder as $file) {
            $files[] = [
                'dir'   => $file->isDir(),
                'name'  => $file->getFilename(),
                'path'  => sprintf('%s/%s', $path, $file->getFilename()),
                'size'  => $file->isDir() ? '' : $file->getSize(),
                'mtime' => Carbon::createFromTimestamp($file->getMTime())->format('Y-m-d H:i:s'),
            ];
        }
        
        return $this->jsonResponse([
            'files'  => $files,
            'inRoot' => $inRoot,
            'cwd'    => $path,
        ]);
    }
    
    private function createFinder(string $path, array $extensions): Finder
    {
        $directory = $this->getKernel()->getRootDir() . '/../' . $path;
        $finder    = new Finder();
        
        $finder->in($directory)->ignoreVCS(true)->sortByType()->depth(0)->filter(function (\SplFileInfo $file) use ($extensions) {
            if ($this->isValidFile($file, $extensions) || $this->isValidDirectory($file)) {
                return true;
            }
            
            return false;
        });
        
        return $finder;
    }
    
    public function uploadLocalAction(Request $request): JsonResponse
    {
        if ($request->files->has('file')) {
            $file = $request->files->get('file');
            if ($file instanceof UploadedFile) {
                $directory = $request->get('cwd');
                $uploadDir = $this->getKernel()->getRootDir() . '/../' . $directory;
                $file->move($uploadDir, $file->getClientOriginalName());
                
                return $this->jsonResponse([
                    'sFilename' => $file->getClientOriginalName(),
                ]);
            }
        }
        
        return $this->jsonResponse([
            'error' => 'No file to upload',
        ]);
    }
    
    private function isValidFile(\SplFileInfo $file, array $extensions): bool
    {
        return $file->isFile() && $file->isReadable() && in_array($file->getExtension(), $extensions);
    }
    
    private function isValidDirectory(\SplFileInfo $file): bool
    {
        return $file->isDir() && $file->isReadable();
    }
    
    private function getUploader(): MediaUploaderInterface
    {
        return $this->get('media.uploader');
    }
}
