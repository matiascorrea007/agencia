<?php

namespace agencia\Http\Controllers;

use Illuminate\Http\Request;
use agencia\Http\Requests;
use agencia\SoapClass;
use agencia\SoapServer;
use Artisaninweb\SoapWrapper\Facades\SoapWrapper;

class PaqueteController extends Controller
{
	public function __construct()
    {
        /*si no existe mi session cart , esntonces la creo con put y creo
        un array para almacenar los items*/
        if(!\Session::has('paquete')) \Session::put('paquete', array());
        if(!\Session::has('paquete2')) \Session::put('paquete2', array());
        if(!\Session::has('paquete3')) \Session::put('paquete3', array());
        //para cliente ya no es un array ya que almaceno 1 solo objeto
        if(!\Session::has('cliente')) \Session::put('cliente');
    }


public function ObtenerCiudades(Request $request)
    {
        
        SoapWrapper::add(function ($service) {
            $service
                ->name('currency')
                ->wsdl('http://ds.dyndns.org:9000/BusService?wsdl');   // Optional: Set some extra options
        });

        $data = [
            'codigo' => '2559d18b-8229-4cb5-9b9c-1ad85207edb6',
            
        ];

        // Using the added service
        SoapWrapper::service('currency', function ($service) use ($data) {
            $service->getFunctions();
            dd($service->call('ObtenerCiudades', [$data]));
        });

    }



    public function ObtenerUnidades(Request $request)
    {
    	
        SoapWrapper::add(function ($service) {
            $service
                ->name('currency')
                ->wsdl('http://ds.dyndns.org:9000/BusService?wsdl');   // Optional: Set some extra options
        });

        $data = [
            'codigo' => '2559d18b-8229-4cb5-9b9c-1ad85207edb6',
            'ciudad'=>1
        ];

        // Using the added service
        SoapWrapper::service('currency', function ($service) use ($data) {
            $service->getFunctions();
            dd($service->call('ObtenerUnidades', [$data]));
        });

    }


    public function VincularUnidad(Request $request)
    {
        
        SoapWrapper::add(function ($service) {
            $service
                ->name('currency')
                ->wsdl('http://ds.dyndns.org:9000/BusService?wsdl');   // Optional: Set some extra options
        });

        $data = [
            'codigo' => '2559d18b-8229-4cb5-9b9c-1ad85207edb6',
            'unidad'=>1
        ];

        // Using the added service
        SoapWrapper::service('currency', function ($service) use ($data) {
            $service->getFunctions();
            dd($service->call('VincularUnidad', [$data]));
        });

    }



public function ObtenerButacas(Request $request)
    {
        
        SoapWrapper::add(function ($service) {
            $service
                ->name('currency')
                ->wsdl('http://ds.dyndns.org:9000/BusService?wsdl');   // Optional: Set some extra options
        });

        $data = [
            'codigo' => '2559d18b-8229-4cb5-9b9c-1ad85207edb6',
            'unidad'=>1
        ];

        // Using the added service
        SoapWrapper::service('currency', function ($service) use ($data) {
            $service->getFunctions();
            dd($service->call('ObtenerButacas', [$data]));
        });

    }


public function ReservarButacas(Request $request)
    {
        
        SoapWrapper::add(function ($service) {
            $service
                ->name('currency')
                ->wsdl('http://ds.dyndns.org:9000/BusService?wsdl');   // Optional: Set some extra options
        });

        $data = [
            'codigo' => '2559d18b-8229-4cb5-9b9c-1ad85207edb6',
            'unidad'=>1,
            'butacas'=>[]
        ];

        // Using the added service
        SoapWrapper::service('currency', function ($service) use ($data) {
            $service->getFunctions();
            dd($service->call('ReservarButacas', [$data]));
        });

    }





}
