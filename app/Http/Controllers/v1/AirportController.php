<?php

namespace App\Http\Controllers\v1;

use App\Services\v1\FlightService;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Services\v1\AirportService;

class AirportController extends Controller
{
    protected $service;
    protected $flights;
    public function __construct(AirportService $aservice, FlightService $service) {
        $this->service = $aservice;

        $this->flights = $service;

//        $this->middleware('auth:api', ['only' => ['store', 'update', 'destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parameters = request()->input();
        $data = $this->service->getAirports($parameters);
        return response()->json($data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $this->flights->validate($request->all());
        try {
            $data = $this->service->store($request);
            return response()->json($data, 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $parameters = request()->input();

        $parameters['flightNumber'] = $id;
        $data = $this->flights->getFlights($parameters);

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->flights->validate($request->all());
        
        try {
            $flight = $this->flights->updateFlight($request, $id);
            return response()->json($flight, 200);
        } 
        catch (ModelNotFoundException $ex) {
            throw $ex;
        }
        catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $flight = $this->flights->deleteFlight($id);
            return response()->make('', 204);
        } 
        catch (ModelNotFoundException $ex) {
            throw $ex;
        }
        catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
