<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\DevicesRepository;
use App\Repository\ValueRepository;
use App\Entity\Devices;
use App\Entity\Value;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;






class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(DevicesRepository $devicesrepository
    ): Response
    {
        if(!$this->isGranted('IS_AUTHENTICATED')) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $devices = $user->getDevices();
        $devicesArray = $devices->toArray();
        $lastDevice = end($devicesArray);
        $device = $devicesrepository->findOneById($lastDevice->getId());
        $values = $device->getValueDevice();
        $valuesArray = $values->toArray();
        $lastValue = end($valuesArray);
        $lastCount = $lastValue->getCount();
        return $this->render('home/index.html.twig', [
                'count' => $lastCount,
        ]);

    }

    #[Route('/api/{nb}/{id}',
    name :'app_device_api', methods:['GET', 'POST'])]
    public function api(Request $request,
    DevicesRepository $devicesrepository,
    ValueRepository $valueRepository): Response {
    $id = $request->attributes->get('id');
    $nb = $request->attributes->get('nb');

    if ($devicesrepository->findOneById($id)){


        $device = $devicesrepository->findOneById($id);
    
        $value = new Value;
        $value->setCount($nb);
        $value->setDevice($device);
        $value->setDate(date('d-m-y h:i:s'));

        $valueRepository->save($value, true);
        return $this->json(['status' => 'success']);
        }
        else {
            return $this->json(['status' => 'error']);
        }
    }


}
