<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eccube\Controller\Admin\Content;

use Eccube\Controller\AbstractController;
use Eccube\Entity\Banner;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Admin\BannerType;
use Eccube\Repository\BannerRepository;
use Eccube\Util\CacheUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class BannerController extends AbstractController
{
    /**
     * @var BannerRepository
     */
    protected $bannerRepository;

    /**
     * BannerController constructor.
     *
     * @param BannerRepository $bannerRepository
     */
    public function __construct(BannerRepository $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/content/banner", name="admin_content_banner")
     * @Template("@admin/Content/banner.twig")
     */
    public function index(Request $request)
    {
        $Banners = $this->bannerRepository->getList();
        $BannerSliders = $this->bannerRepository->getListBannerSliders();
        $BannerTopSPs = $this->bannerRepository->getListBannerTopSPs();

        $event = new EventArgs(
            [
                'Banners' => $Banners,
            ],
            $request
        );
        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_CONTENT_BANNER_INDEX_COMPLETE, $event);

        return [
            'Banners' => $Banners,
            'BannerSliders' => $BannerSliders,
            'BannerTopSPs' => $BannerTopSPs,
        ];
    }

    /**
     * バナーを登録・編集する。
     *
     * @Route("/%eccube_admin_route%/content/banner/new", name="admin_content_banner_new")
     * @Route("/%eccube_admin_route%/content/banner/{id}/edit", requirements={"id" = "\d+"}, name="admin_content_banner_edit")
     * @Template("@admin/Content/banner_edit.twig")
     *
     * @param Request $request
     * @param null $id
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function edit(Request $request, $id = null, CacheUtil $cacheUtil)
    {
        if ($id) {
            $Banner = $this->bannerRepository->find($id);
            if (!$Banner) {
                throw new NotFoundHttpException();
            }
        } else {
            $Banner = new \Eccube\Entity\Banner();
        }

        if ($request->get('type')) {
            $Banner->setType($request->get('type'));
            if ($request->get('type') == 2) {
                $Banner->setDevice(2);
            }
        }

        $builder = $this->formFactory
            ->createBuilder(BannerType::class, $Banner);

        $event = new EventArgs(
            [
                'builder' => $builder,
                'Banner' => $Banner,
            ],
            $request
        );
        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_CONTENT_BANNER_EDIT_INITIALIZE, $event);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Banner = $form->getData();

            $allowExtensions = ['gif', 'jpg', 'jpeg', 'png', 'svg'];
            $image = $form->get('image')->getData();
            if ($image && in_array(strtolower($image->getClientOriginalExtension()), $allowExtensions)) {
                if ($Banner->getImage() && file_exists('html/upload/save_banner/'.$Banner->getImage())) {
                    unlink('html/upload/save_banner/'.$Banner->getImage());
                }
                $extension = $image->getClientOriginalExtension();
                $filename = date('mdHis').uniqid('_').'.'.$extension;
                $image->move('html/upload/save_banner/', $filename);

                $Banner->setImage($filename);
            }
            $this->entityManager->persist($Banner);
            $this->entityManager->flush();

            $event = new EventArgs(
                [
                    'form' => $form,
                    'Banner' => $Banner,
                ],
                $request
            );
            $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_CONTENT_BANNER_EDIT_COMPLETE, $event);

            $this->addSuccess('admin.common.save_complete', 'admin');

            // キャッシュの削除
            $cacheUtil->clearDoctrineCache();

            return $this->redirectToRoute('admin_content_banner_edit', ['id' => $Banner->getId()]);
        }

        return [
            'form' => $form->createView(),
            'Banner' => $Banner,
            'type' => $request->get('type') ? $request->get('type') : $Banner->getType(),
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/content/banner/{id}/delete", requirements={"id" = "\d+"}, name="admin_content_banner_delete", methods={"DELETE"})
     */
    public function delete(Request $request, $id = null, CacheUtil $cacheUtil)
    {
        $this->isTokenValid();

        $Banner = $this->bannerRepository
            ->findOneBy([
                'id' => $id,
            ]);

        if (!$Banner) {
            $this->deleteMessage();

            return $this->redirectToRoute('admin_content_banner');
        }

        if ($Banner->getImage() && file_exists('html/upload/save_banner/'.$Banner->getImage())) {
            unlink('html/upload/save_banner/'.$Banner->getImage());
        }
        $this->entityManager->remove($Banner);
        $this->entityManager->flush();

        $event = new EventArgs(
            [
                'Banner' => $Banner,
            ],
            $request
        );
        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_CONTENT_BANNER_DELETE_COMPLETE, $event);

        $this->addSuccess('admin.common.delete_complete', 'admin');

        // キャッシュの削除
        $cacheUtil->clearTwigCache();
        $cacheUtil->clearDoctrineCache();

        return $this->redirectToRoute('admin_content_banner');
    }
}
