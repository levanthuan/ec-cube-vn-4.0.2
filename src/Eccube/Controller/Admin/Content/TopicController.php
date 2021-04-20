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
use Eccube\Entity\Topic;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Admin\TopicType;
use Eccube\Repository\TopicRepository;
use Eccube\Util\CacheUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TopicController extends AbstractController
{
    /**
     * @var TopicRepository
     */
    protected $TopicRepository;

    /**
     * TopicController constructor.
     *
     * @param TopicRepository $TopicRepository
     */
    public function __construct(TopicRepository $TopicRepository)
    {
        $this->TopicRepository = $TopicRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/content/topic", name="admin_content_topic")
     * @Template("@admin/Content/topic.twig")
     */
    public function index(Request $request)
    {
        $Topics = $this->TopicRepository->getList();

        $event = new EventArgs(
            [
                'Topics' => $Topics,
            ],
            $request
        );
        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_CONTENT_TOPIC_INDEX_COMPLETE, $event);

        return [
            'Topics' => $Topics
        ];
    }

    /**
     * バナーを登録・編集する。
     *
     * @Route("/%eccube_admin_route%/content/topic/new", name="admin_content_topic_new")
     * @Route("/%eccube_admin_route%/content/topic/{id}/edit", requirements={"id" = "\d+"}, name="admin_content_topic_edit")
     * @Template("@admin/Content/topic_edit.twig")
     *
     * @param Request $request
     * @param null $id
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function edit(Request $request, $id = null, CacheUtil $cacheUtil)
    {
        if ($id) {
            $Topic = $this->TopicRepository->find($id);
            if (!$Topic) {
                throw new NotFoundHttpException();
            }
        } else {
            $Topic = new \Eccube\Entity\Topic();
        }

        $builder = $this->formFactory
            ->createBuilder(TopicType::class, $Topic);

        $event = new EventArgs(
            [
                'builder' => $builder,
                'Topic' => $Topic,
            ],
            $request
        );
        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_CONTENT_TOPIC_EDIT_INITIALIZE, $event);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Topic = $form->getData();

            $allowExtensions = ['gif', 'jpg', 'jpeg', 'png', 'svg'];
            $image = $form->get('image')->getData();
            if ($image && in_array(strtolower($image->getClientOriginalExtension()), $allowExtensions)) {
                if ($Topic->getImage() && file_exists('html/upload/save_banner/'.$Topic->getImage())) {
                    unlink('html/upload/save_banner/'.$Topic->getImage());
                }
                $extension = $image->getClientOriginalExtension();
                $filename = date('mdHis').uniqid('_').'.'.$extension;
                $image->move('html/upload/save_banner/', $filename);

                $Topic->setImage($filename);
            }
            $this->entityManager->persist($Topic);
            $this->entityManager->flush();

            $event = new EventArgs(
                [
                    'form' => $form,
                    'Topic' => $Topic,
                ],
                $request
            );
            $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_CONTENT_TOPIC_EDIT_COMPLETE, $event);

            $this->addSuccess('admin.common.save_complete', 'admin');

            // キャッシュの削除
            $cacheUtil->clearDoctrineCache();

            return $this->redirectToRoute('admin_content_topic_edit', ['id' => $Topic->getId()]);
        }

        return [
            'form' => $form->createView(),
            'Topic' => $Topic
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/content/topic/{id}/delete", requirements={"id" = "\d+"}, name="admin_content_topic_delete", methods={"DELETE"})
     */
    public function delete(Request $request, $id = null, CacheUtil $cacheUtil)
    {
        $this->isTokenValid();

        $Topic = $this->TopicRepository
            ->findOneBy([
                'id' => $id,
            ]);

        if (!$Topic) {
            $this->deleteMessage();

            return $this->redirectToRoute('admin_content_topic');
        }

        if ($Topic->getImage() && file_exists('html/upload/save_banner/'.$Topic->getImage())) {
            unlink('html/upload/save_banner/'.$Topic->getImage());
        }
        $this->entityManager->remove($Topic);
        $this->entityManager->flush();

        $event = new EventArgs(
            [
                'Topic' => $Topic,
            ],
            $request
        );
        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_CONTENT_TOPIC_DELETE_COMPLETE, $event);

        $this->addSuccess('admin.common.delete_complete', 'admin');

        // キャッシュの削除
        $cacheUtil->clearTwigCache();
        $cacheUtil->clearDoctrineCache();

        return $this->redirectToRoute('admin_content_topic');
    }
}
