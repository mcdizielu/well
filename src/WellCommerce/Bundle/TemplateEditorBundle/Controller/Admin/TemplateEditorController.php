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

namespace WellCommerce\Bundle\TemplateEditorBundle\Controller\Admin;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CoreBundle\Controller\Admin\AbstractAdminController;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class TemplateEditorController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class TemplateEditorController extends AbstractAdminController
{
    const ALLOWED_FILE_EXTENSIONS = ['html', 'twig', 'css', 'less', 'js', 'png', 'jpg', 'jpeg', 'gif', 'xml', 'json'];
    
    public function editAction(int $id): Response
    {
        $resource = $this->getManager()->getRepository()->find($id);
        
        if (!$resource instanceof EntityInterface) {
            return $this->redirectToAction('index');
        }
        
        return $this->displayTemplate('edit', [
            'resource' => $resource,
        ]);
    }
    
    public function listFilesAction(Request $request, string $theme): Response
    {
        $treeContent = $this->renderView('WellCommerceTemplateEditorBundle:Admin/TemplateEditor:tree.html.twig', [
            'finder' => $this->createFinder($theme, $request->get('dir')),
            'dir'    => $request->get('dir'),
        ]);
        
        return new Response($treeContent);
    }
    
    public function getFileContentAction(Request $request): Response
    {
        $fileName = $request->get('file');
        $theme    = $request->get('theme');
        $path     = $this->getFilePath($theme, $fileName);
        $content  = file_get_contents($path);
        
        return $this->jsonResponse([
            'theme'     => $theme,
            'file'      => $fileName,
            'path'      => $path,
            'content'   => $content,
            'extension' => pathinfo($path, PATHINFO_EXTENSION),
        ]);
    }
    
    public function saveFileAction(Request $request): Response
    {
        $fileName   = $request->get('file');
        $theme      = $request->get('theme');
        $content    = $request->get('content');
        $path       = $this->getFilePath($theme, $fileName);
        $filesystem = new Filesystem();
        
        if (false === $filesystem->exists($path)) {
            return $this->jsonResponse([
                'error' => 'Cannot save file',
            ]);
        }
        
        try {
            $filesystem->dumpFile($path, $content);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'error' => $e->getMessage(),
            ]);
        }
        
        return $this->jsonResponse([
            'success' => true,
        ]);
    }
    
    public function uploadFileAction(Request $request): Response
    {
        
    }
    
    private function getFilePath(string $theme, string $fileName): string
    {
        if (strpos($fileName, '..') !== false) {
            throw new \Exception('Wrong file path');
        }
        
        return sprintf('%s/../web/themes/%s/%s', $this->get('kernel')->getRootDir(), $theme, $fileName);
    }
    
    private function createFinder(string $theme, string $rootDir): Finder
    {
        $directory = $this->getFilePath($theme, $rootDir);
        $finder    = new Finder();
        
        $finder->in($directory)->ignoreVCS(true)->sortByType()->depth(0)->filter(function (\SplFileInfo $file) {
            if ($this->isValidFile($file) || $this->isValidDirectory($file)) {
                return true;
            }
            
            return false;
        });
        
        return $finder;
    }
    
    private function isValidFile(\SplFileInfo $file): bool
    {
        return $file->isFile() && $file->isWritable() && in_array($file->getExtension(), self::ALLOWED_FILE_EXTENSIONS);
    }
    
    private function isValidDirectory(\SplFileInfo $file): bool
    {
        return $file->isDir() && $file->isReadable();
    }
}
