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

namespace Eccube\Controller\Admin\Setting\Shop;

use Eccube\Controller\AbstractController;
use Eccube\Entity\Holiday;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Admin\HolidayType;
use Eccube\Repository\HolidayRepository;
use Eccube\Util\CacheUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HolidayController
 */
class HolidayController extends AbstractController
{
    /**
     * @var HolidayRepository
     */
    protected $holidayRepository;

    /**
     * DenyMailController constructor.
     *
     * @param HolidayRepository $holidayRepository
     */
    public function __construct(HolidayRepository $holidayRepository)
    {
        $this->holidayRepository = $holidayRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/setting/shop/holiday", name="admin_setting_shop_holiday")
     * @Template("@admin/Setting/Shop/holiday.twig")
     */
    public function index(Request $request, CacheUtil $cacheUtil)
    {
        $Holidays = $this->holidayRepository->getList();

        $Holiday = new \Eccube\Entity\Holiday();

        $builder = $this->formFactory
            ->createBuilder(HolidayType::class, $Holiday);

        $event = new EventArgs(
            [
                'Holidays' => $Holidays,
            ],
            $request
        );
        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_SETTING_SHOP_HOLIDAY_INDEX_COMPLETE, $event);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Holiday = $form->getData();

            if ($this->holidayRepository->findOneBy(['postal_code' => $Holiday->getPostalCode()])) {
                $this->addError('離島は既に存在します。', 'admin');
                return $this->redirectToRoute('admin_setting_shop_holiday');
            }

            $this->entityManager->persist($Holiday);
            $this->entityManager->flush();

            $this->addSuccess('admin.common.save_complete', 'admin');

            // キャッシュの削除
            $cacheUtil->clearDoctrineCache();
            return $this->redirectToRoute('admin_setting_shop_holiday');
        }

        return [
            'form' => $form->createView(),
            'Holidays' => $Holidays
        ];
    }

    /**
     * バナーを登録・編集する。
     *
     * @Route("/%eccube_admin_route%/setting/shop/holiday/new", name="admin_setting_shop_holiday_new")
     * @Route("/%eccube_admin_route%/setting/shop/holiday/{id}/edit", requirements={"id" = "\d+"}, name="admin_setting_shop_holiday_edit")
     * @Template("@admin/Setting/Shop/holiday_edit.twig")
     *
     * @param Request $request
     * @param null $id
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function edit(Request $request, $id = null, CacheUtil $cacheUtil)
    {
        if ($id) {
            $Holiday = $this->holidayRepository->find($id);
            if (!$Holiday) {
                throw new NotFoundHttpException();
            }
        } else {
            $Holiday = new \Eccube\Entity\Holiday();
        }

        $builder = $this->formFactory
            ->createBuilder(HolidayType::class, $Holiday);

        $event = new EventArgs(
            [
                'builder' => $builder,
                'Holiday' => $Holiday,
            ],
            $request
        );
        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_SETTING_SHOP_HOLIDAY_EDIT_INITIALIZE, $event);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Holiday = $form->getData();
            $this->entityManager->persist($Holiday);
            $this->entityManager->flush();

            $event = new EventArgs(
                [
                    'form' => $form,
                    'Holiday' => $Holiday,
                ],
                $request
            );
            $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_SETTING_SHOP_HOLIDAY_EDIT_COMPLETE, $event);

            $this->addSuccess('admin.common.save_complete', 'admin');

            // キャッシュの削除
            $cacheUtil->clearDoctrineCache();

            return $this->redirectToRoute('admin_setting_shop_holiday_edit', ['id' => $Holiday->getId()]);
        }

        return [
            'form' => $form->createView(),
            'Holiday' => $Holiday
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/shop/holiday/{id}/delete", requirements={"id" = "\d+"}, name="admin_setting_shop_holiday_delete", methods={"DELETE"})
     */
    public function delete(Request $request, $id = null, CacheUtil $cacheUtil)
    {
        $this->isTokenValid();

        $Holiday = $this->holidayRepository
            ->findOneBy([
                'id' => $id,
            ]);

        if (!$Holiday) {
            $this->deleteMessage();

            return $this->redirectToRoute('admin_setting_shop_holiday');
        }

        $this->entityManager->remove($Holiday);
        $this->entityManager->flush();

        $event = new EventArgs(
            [
                'Holiday' => $Holiday,
            ],
            $request
        );
        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_SETTING_SHOP_HOLIDAY_DELETE_COMPLETE, $event);

        $this->addSuccess('admin.common.delete_complete', 'admin');

        // キャッシュの削除
        $cacheUtil->clearTwigCache();
        $cacheUtil->clearDoctrineCache();

        return $this->redirectToRoute('admin_setting_shop_holiday');
    }
}