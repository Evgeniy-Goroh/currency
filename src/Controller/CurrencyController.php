<?php

namespace App\Controller;

use App\Services\ApiServices;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

/**
 * Class CurrencyController
 * @package App\Controller
 *
 * @Route("/api")
 */
class CurrencyController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var TranslatorInterface
     */
    private $translation;

    /**
     * @var ApiServices
     */
    private $apiServices;

    /**
     * CallbackController constructor.
     */
    public function __construct(
        LoggerInterface $logger,
        TranslatorInterface $translation,
        ApiServices $apiServices
    ) {
        $this->logger = $logger;
        $this->translation = $translation;
        $this->apiServices = $apiServices;
    }
    /**
     * @Route("/currency", name="currency",  methods={"GET"})
     */
    public function index(Request $request): JsonResponse
    {
        $result['success'] = false;

        try {
            $result['result'] = $this->apiServices->getCurrensy($request);
        } catch (Throwable $exception) {
            $this->logger->error($this->translation->trans('error.not_found', [], 'currency'));
            $result['errorMsg'] = $this->translation->trans('error.not_found', [], 'currency');

            return new JsonResponse($result, Response::HTTP_NOT_FOUND);
        }

        $result['success'] = true;

        return new JsonResponse($result, Response::HTTP_OK);
    }

    /**
     * @Route("/currency/{id}", name="currency_get", methods={"GET"})
     */
    public function getById(Request $request): JsonResponse
    {
        $id = $request->get('id');
        $result['success'] = false;

        try {
            $result['result'] = $this->apiServices->getCurrensyById($id);
        } catch (Throwable $exception) {
            $this->logger->error($this->translation->trans('error.not_found', [], 'currency'));
            $result['errorMsg'] = $this->translation->trans('error.not_found', [], 'currency');

            return new JsonResponse($result, Response::HTTP_NOT_FOUND);
        }

        $result['success'] = true;

        return new JsonResponse($result, Response::HTTP_OK);
    }
}
