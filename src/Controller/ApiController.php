<?php
namespace App\Controller;

use App\Repository\DevicesRepository;
use App\Repository\UserRepository;
use App\Repository\ValueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/app-api')]
class ApiController extends AbstractController
{

    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $encoderService): Response {

        $user = $userRepository->findOneBy(['email' => $request->get('email')]);
        $password = $request->get('password');

        if($encoderService->isPasswordValid($user, $password)) {

            $data = array(
                'id' => $user->getId(),
                'email' => $request->get('email'),
                'password' => $request->get('password'),
            );

            return $this->json(
                $data,
                headers: ['Content-Type' => 'application/json;charset=UTF-8']
            );
        }
        return $this->json([false]);
    }

    #[Route('/get', name: 'api_get_datas', methods: ['POST'])]
    public function get(Request $request, UserRepository $userRepository,  DevicesRepository $deviceRepository,
                        ValueRepository $valueRepository, UserPasswordHasherInterface $encoderService): Response {

        $user = $userRepository->findOneBy(['email' => $request->get('email')]);
        $password = $request->get('password');

        if($encoderService->isPasswordValid($user, $password)) {

            $devices = $user->getDevices();
            $devicesArray = $devices->toArray();
            $lastDevice = end($devicesArray);
            $device = $deviceRepository->findOneById($lastDevice->getId());
            $values = $device->getValueDevice();
            $valuesArray = $values->toArray();
            $lastValue = end($valuesArray);
            $lastCount = $lastValue->getCount();

            $data = array();

            foreach($devices as $device) {
                $data[] = array(
                    'value' => strval($lastCount),
                );
            }

            return $this->json(
                $data,
                headers: ['Content-Type' => 'application/json;charset=UTF-8']
            );
        }
        return $this->json([false]);
    }
}