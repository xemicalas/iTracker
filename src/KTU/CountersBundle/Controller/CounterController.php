<?php

namespace KTU\CountersBundle\Controller;

use KTU\CountersBundle\Entity\Counters;
use KTU\CountersBundle\Form\CounterType;
use KTU\CountersBundle\Form\CounterColorType;
use KTU\CountersBundle\Model\CategoriesModel;
use KTU\CountersBundle\Model\CountersModel;
use KTU\CountersBundle\Model\CounterStatisticsModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class CounterController. Skaitliuko kontroleris, kuris kontroliuoja skaitliuko
 * atvaidavimą, kūrimą, trinimą, redagavimą.
 * @package KTU\CountersBundle\Controller
 */
class CounterController extends Controller
{

    /**
     * Atvaizduoja skaitliuko informaciją pagal nurodyta skaitliuko ID
     * @param Counters $counter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCounterAction(Counters $counter)
    {
        $manager = $this->getDoctrine()->getManager();
        $category = CategoriesModel::getCategoryById($manager, $counter->getCat());

        // Gauną skaitliuko paskutinių 10 dienų statistiką
        $stats = CounterStatisticsModel::getLastStatsByCountersId($manager, $counter->getId(), -14);

        // Gauna absoliučius skaitliuko statistinius duomenis
        $totals = CounterStatisticsModel::getTotalStatsByCountersId($manager, $counter->getId());

        return $this->render('KTUCountersBundle:Counter:showCounter.html.twig', array(
            'counter' => $counter,
            'category' => $category,
            'stats' => $stats,
            'totals' => $totals
        ));
    }

    /**
     * Atvaizduoja skaitliuko kūrimo puslapio formą.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function createCounterAction(Request $request)
    {
        if (!$this->get('security.context')->isGranted(new Expression('"ROLE_USER" in roles'))) {
            throw new AccessDeniedException();
        }
        $isCreated = false;
        $manager = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $counter = new Counters();
        $translator = $this->container->get('translator');
        $createText = $translator->trans('counter.create.form.submit');
        $form = $this->createForm(new CounterType($createText, 'create', 'KTUCountersBundle'), $counter);

        // Jei forma buvo patvirtinta
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $data = $form->getData();

            $counters = CountersModel::getCountersByUserId($manager, $user->getId());
            $cat = CategoriesModel::getCategoryById($manager, $data->getCat());

            if (count($counters) >= 5) {
                $form->addError(new FormError(
                    $translator->trans('counter.create.form.alerts.limit', array(), 'KTUCountersBundle')));
            }
            if ($cat == null) {
                $form->addError(new FormError(
                    $translator->trans('counter.create.form.alerts.exists', array(), 'KTUCountersBundle')));
            }
            // Patikrinama ar teisingas skaitliuko URL adreso formatas
            if (!preg_match('/^(www\.)?([A-Za-z0-9-]+\.)+\w{2,4}/i', $data->getUrl())) {
                $form->addError(new FormError(
                    $translator->trans('counter.create.form.alerts.url_format', array(), 'KTUCountersBundle')));
            }

            // Jei patvirtinta forma yra teisinga, tuomet kuriamas skaitliukas
            if ($form->isValid()) {
                $isCreated = true;
                $counter = new Counters();
                $counter->setUserId($user);
                $counter->setCat($cat);
                $counter->setName($data->getName());
                $counter->setUrl($data->getUrl());
                $counter->setCounterdesc($data->getCounterDesc());
                $counter->setBackgroundColor($this->container->getParameter('ktu_counters.background_color'));
                $counter->setBorderColor($this->container->getParameter('ktu_counters.border_color'));
                $counter->setTextColor($this->container->getParameter('ktu_counters.text_color'));
                $counter->setUniqueColor($this->container->getParameter('ktu_counters.unique_color'));
                $counter->setTotalColor($this->container->getParameter('ktu_counters.total_color'));
                $counter->setBarTotalColor($this->container->getParameter('ktu_counters.bar_total_color'));
                $counter->setBarUniqueColor($this->container->getParameter('ktu_counters.bar_unique_color'));
                $counter->setTransparentBackground($this->container->getParameter('ktu_counters.transparent_background'));

                $manager->persist($counter);
                $manager->flush();
            }
        }

        return $this->render('KTUCountersBundle:Counter:createCounter.html.twig', array(
            'form' => $form->createView(),
            'isCreated' => $isCreated,
            'counter' => $counter
        ));
    }

    /**
     * Atvaizduoja skaitliuko redagavimo puslapio formą.
     * @param Request $request
     * @param Counters $counter
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editCounterAction(Request $request, Counters $counter)
    {
        if (!$this->get('security.context')->isGranted(new Expression('"ROLE_USER" in roles'))) {
            throw new AccessDeniedException();
        }
        $isEdited = false;
        $manager = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $translator = $this->container->get('translator');

        // Leidžiama redaguoti tik vartotojo skaitliuką
        if ($user->getId() == $counter->getUserId()->getId()) {
            $editText = $translator->trans('counter.edit.form.submit');
            $counterUrl = $counter->getUrl();
            $form = $this->createForm(new CounterType($editText, 'edit', 'KTUCountersBundle'), $counter);

            // Jei forma buvo patvirtinta
            if ($request->getMethod() == 'POST') {
                $form->handleRequest($request);
                $data = $form->getData();
                $cat = CategoriesModel::getCategoryById($manager, $data->getCat());
                $counterByUrl = CountersModel::getCounterByUrl($manager, $data->getUrl());

                if ($cat == null) {
                    $form->addError(new FormError(
                        $translator->trans('counter.edit.form.alerts.exists', array(), 'KTUCountersBundle')));
                }
                if ($counterByUrl != null && $counterByUrl->getUrl() != $counterUrl) {
                    $form->addError(new FormError(
                        $translator->trans('counter.edit.form.alerts.url_exists', array(), 'KTUCountersBundle')));
                }
                // Patikrinama ar teisingas skaitliuko URL adreso formatas
                if (!preg_match('/^(www\.)?([A-Za-z0-9-]+\.)+\w{2,4}/i', $data->getUrl())) {
                    $form->addError(new FormError(
                        $translator->trans('counter.edit.form.alerts.url_format', array(), 'KTUCountersBundle')));
                }

                // Jei viskas tvarkinga, tuomet atnaujinami skaitliuko duomenys
                if ($form->isValid()) {
                    $isEdited = true;
                    $counter->setCat($cat);
                    $counter->setName($data->getName());
                    $counter->setUrl($data->getUrl());
                    $counter->setCounterdesc($data->getCounterDesc());
                    $manager->flush();
                }
            }
        } else {
            throw new NotFoundHttpException('This counter doesn\'t belong to this user.');
        }

        return $this->render('KTUCountersBundle:Counter:editCounter.html.twig', array(
            'form' => $form->createView(),
            'isEdited' => $isEdited,
            'counter' => $counter
        ));
    }

    /**
     * Kontroliuoja skaitliuko trynimą
     * @param Counters $counter
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function deleteCounterAction(Counters $counter)
    {
        if (!$this->get('security.context')->isGranted(new Expression('"ROLE_USER" in roles'))) {
            throw new AccessDeniedException();
        }
        $manager = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();

        // Trinti leidžiama tik vartotojo skaitliuką
        if ($user->getId() == $counter->getUserId()->getId()) {
            $stats = CounterStatisticsModel::getStatsByCounterId($manager, $counter->getId());

            foreach ($stats as $stat) {
                $manager->remove($stat);
            }

            $manager->remove($counter);
            $manager->flush();
        } else {
            throw new NotFoundHttpException('This counter doesn\'t belong to this user.');
        }

        return $this->redirect($this->generateUrl('ktu_counters_show_profile'));
    }

    /**
     * Atvaizduoja skaitliuko spalvų keitimo formą.
     * @param Request $request
     * @param Counters $counter
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editCounterColorsAction(Request $request, Counters $counter)
    {
        if (!$this->get('security.context')->isGranted(new Expression('"ROLE_USER" in roles'))) {
            throw new AccessDeniedException();
        }
        $isEdited = false;
        $manager = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();

        // Redaguoti leidžiama tik vartotojo skaitliuką
        if ($user->getId() == $counter->getUserId()->getId()) {
            $form = $this->createForm(new CounterColorType('editColors', 'KTUCountersBundle'), $counter);
            if ($request->getMethod() == 'POST') {
                $form->handleRequest($request);

                if ($form->isValid()) {
                    $isEdited = true;
                    $manager->persist($counter);
                    $manager->flush();
                }
            }
        } else {
            throw new NotFoundHttpException('This counter doesn\'t belong to this user.');
        }

        return $this->render('KTUCountersBundle:Counter:editCounterColors.html.twig', array('form' => $form->createView(),
            'counter' => $counter,
            'isEdited' => $isEdited
        ));
    }
}