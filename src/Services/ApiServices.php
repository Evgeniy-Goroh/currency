<?php

namespace App\Services;

use App\Repository\CurrencyRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class ApiServices
 */
class ApiServices
{
    /**
     * @var CurrencyRepository
     */
    private $currencyRepository;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * ActivityService constructor.
     */
    public function __construct(
        CurrencyRepository $currencyRepository,
        PaginatorInterface $paginator
    ) {
        $this->currencyRepository = $currencyRepository;
        $this->paginator = $paginator;

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getCurrensy(Request $request): string
    {
        $query = $this->currencyRepository->getCurrencyPage();

        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $this->serializer->serialize($pagination->getItems(), 'json');
    }

    /**
     * @param $id
     * @return string
     */
    public function getCurrensyById($id): string
    {
        $currency = $this->currencyRepository->findOneBy(['id'=>$id]);

        return $this->serializer->serialize($currency, 'json');

    }
}
