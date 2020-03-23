<?php

namespace App\Services\v1;

use Validator;

use App\Flight;
use App\Airport;

class AirportService {
    protected $supportedIncludes = [
        'arrivalAirport' => 'arrival',
        'departureAirport' => 'departure'
    ];

    protected $clauseProperties = [
        'status',
        'flightNumber'
    ];

    protected $rules = [
        'flightNumber' => 'required',
        'status' => 'required|flightstatus',
        'arrival.datetime' => 'required|date',
        'arrival.iataCode' => 'required',
        'departure.datetime' => 'required|date',
        'departure.iataCode' => 'required',
    ];

    public function validate($flight) {
        $validator = Validator::make($flight, $this->rules);
        $validator->validate();
    }
    
    public function getAirports($parameters) {
//        if (empty($parameters)) {
//            return $this->filterFlights(Airport::all());
//        }

//        $withKeys = $this->getWithKeys($parameters);
//        $whereClauses = $this->getWhereClause($parameters);

//        $airports = Airport::with($withKeys)->where($whereClauses)->get();

//        return $this->filterFlights($airports, $withKeys);

        return Airport::all();
        
    }

    public function store($req) {
        $obj = new Airport();
        $obj->iataCode = $req->input('iataCode');
        $obj->city =$req->input('city');
        $obj->state =$req->input('state');
        $obj->save();
        return $obj;
    }

    public function updateFlight($req, $flightNumber) {
//        $flight = Flight::where('flightNumber', $flightNumber)->firstOrFail();
//
//        $arrivalAirport = $req->input('arrival.iataCode');
//        $departureAirport = $req->input('departure.iataCode');
//
//        $airports = Airport::whereIn('iataCode', [$arrivalAirport, $departureAirport])->get();
//        $codes = [];
//
//        foreach ($airports as $port) {
//            $codes[$port->iataCode] = $port->id;
//        }
//
//
//        $flight->flightNumber = $req->input('flightNumber');
//        $flight->status = $req->input('status');
//        $flight->arrivalAirport_id = $codes[$arrivalAirport];
//        $flight->arrivalDateTime = $req->input('arrival.datetime');
//        $flight->depatureAirport_id = $codes[$departureAirport];
//        $flight->depatureDateTime = $req->input('departure.datetime');
//
//        $flight->save();
//
//        return $this->filterFlights([$flight]);
    }

    public function deleteFlight($flightNumber) {
//        $flight = Flight::where('flightNumber', $flightNumber)->firstOrFail();
//
//
//        $flight->delete();
    }

//    protected function filter($objs, $keys = []) {
//        $data = [];
//
//        foreach ($objs as $obj) {
//            $entry = [
////                'iataCode' => $obj->flightNumber,
////                'city' => $obj->status,
////                'state' => $obj->status,
////                'href' => route('flights.show', ['id' => $obj->flightNumber])
//            ];
//
////            if (in_array('arrivalAirport', $keys)) {
////                $entry['arrival'] = [
////                    'datetime' => $obj->arrivalDateTime,
////                    'iataCode' => $obj->arrivalAirport->iataCode,
////                    'city' => $obj->arrivalAirport->city,
////                    'state' => $obj->arrivalAirport->state,
////                ];
////            }
////
////            if (in_array('departureAirport', $keys)) {
////                $entry['departure'] = [
////                    'datetime' => $obj->depatureDateTime,
////                    'iataCode' => $obj->departureAirport->iataCode,
////                    'city' => $obj->departureAirport->city,
////                    'state' => $obj->departureAirport->state,
////                ];
////            }
//
//            $data[] = $entry;
//        }
//
//        return $data;
//    }

    protected function getWithKeys($parameters) {
        $withKeys = [];

        if (isset($parameters['include'])) {
            $includeParms = explode(',', $parameters['include']);
            $includes = array_intersect($this->supportedIncludes, $includeParms);
            $withKeys = array_keys($includes);
        }

        return $withKeys;
    }

    protected function getWhereClause($parameters) {
        $clause = [];

        foreach ($this->clauseProperties as $prop) {
            if (in_array($prop, array_keys($parameters))) {
                $clause[$prop] = $parameters[$prop];
            }
        }

        return $clause;
    }
}