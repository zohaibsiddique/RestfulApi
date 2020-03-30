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
    public function __construct(AirportService $aservice) {
        $this->service = $aservice;

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
        try {
            if($request->input('push_list')){
                $data =  $this->service->pushList($request);
                return response()->json($data, 201);
            }else{
                $data =  $this->service->store($request);
                return response()->json($data, 201);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
//        $this->flights->validate($request->all());
//        try {
//            return $this->service->store($request);
////            $data =  $this->service->store($request);
////            return response()->json($data, 201);
//        } catch (Exception $e) {
//            return response()->json(['message' => $e->getMessage()], 500);
//        }
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
//        $this->service->validate($request->all());

        try {
            if($request->input('update_list')){
                $data =  $this->service->updateList($request);
                return response()->json($data, 201);
            }else{
                $obj = $this->service->update($request, $id);
                return response()->json($obj, 200);
            }
        } catch (Exception $e) {
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
            $obj = $this->service->delete($id);
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
