<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DisbursmentResource;
use App\Repositories\DisbursmentRepository;
use App\Models\Disbursment;
use App\Services\Flip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DisbursmentController extends Controller
{
    protected $repository;
    protected $endpoint;
    protected $flip;

     /**
      * Load default class depedencies.
      *
      * @param Model model Illuminate\Database\Eloquent\Model
      */
    public function __construct(DisbursmentRepository $repository, Flip $flip)
    {
        $this->repository = $repository;
        $this->flip = $flip;
        $this->endpoint = 'disburse';
    }

    /**
     * @OA\Get(
     *   path="/api/disbursments",
     *     tags={"disbursment"},
     *     summary="Fetch all disbursment",
     *     description="Returns all disbursment",
     *     operationId="listDisbursments",
     *     @OA\Response(
     *         response=200,
     *         description="List of disbursments",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                          allOf={
     *                              @OA\Schema(ref="#/components/schemas/Disbursment")
     *                          }
     *                      )
     *                 )
     *             )
     *         )
     *     )
     * )
     *
     * @return Illuminate\Http\Resources\Json\Resource
     */
    public function index()
    {
        $disbursments = $this->repository->getAll();

        return DisbursmentResource::collection($disbursments);
    }

    /**
     * @OA\Get(
     *   path="/api/v1/admin/disbursments/{disbursment}",
     *     tags={"disbursment"},
     *     summary="Get disbursment by ID",
     *     description="Returns a single disbursment data",
     *     operationId="getDisbursmentID",
     *     @OA\Parameter(
     *         name="disbursment",
     *         in="path",
     *         description="ID of disbursment to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                          allOf={
     *                              @OA\Schema(ref="#/components/schemas/Disbursment")
     *                          }
     *                      )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Disbursment not found"
     *     ),
     * )
     *
     * @param  int  $disbursment
     * @return Illuminate\Http\Resources\Json\Resource
     */
    
    public function show($disbursmentID)
    {
        $disbursment = Disbursment::find($disbursmentID);
        if (empty($disbursment)) {
            $error['data'] = null;
            $error['status'] = 404;
            $error['message'] = "Disbursment not found!.";
            $error['errors'] = null;
            return response()->json($error, 404);
        }

        $flip = $this->flip->getByID($this->endpoint, $disbursment->transaction_id);
        $disbursment->update(['status' => $flip['status']]);

        return new DisbursmentResource($disbursment);
    }

    /**
     * @OA\Post(
     *  path="/api/disbursments/create",
     *  tags={"disbursment"},
     *  summary="Create new",
     *  description="Add new disbursment data",
     *  operationId="addDisbursment",
     *  @OA\RequestBody(
     *      required=true,
     *      description="",
     *      @OA\MediaType(
     *          mediaType="application/x-www-form-urlencoded",
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="amount",
     *                  type="integer"
     *              ),
     *              @OA\Property(
     *                  property="bank_code",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="account_number",
     *                  type="integer"
     *              ),
     *              @OA\Property(
     *                  property="remark",
     *                  type="string"
     *              ),
     *          )
     *      )
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="Missing field parameter"
     *  ),
     *  @OA\Response(
     *      response=201,
     *      description="Successful operation"
     *  )
     * )
     */
    public function store(Request $request)
    {
        $rules = [
            "amount" => ['required', 'numeric'],
            "bank_code" => ['required'],
            "account_number" => ['required', 'numeric'],
            "remark" => ['required'],
        ];

        $message = [
            'required' => 'The :attribute field is required.',
            'numeric' => 'The :attribute field is must numeric.'
        ];
        
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            $error['data'] = null;
            $error['status'] = 422;
            $error['message'] = $validator->messages()->first();
            $error['errors'] = null;
            return response()->json($error, 422);
        }

        $flip = $this->flip->createNew($this->endpoint, $request->all());

        DB::beginTransaction();

        try {
            $disbursment = Disbursment::create(
                array_merge(
                    $request->all(), 
                    [
                        'status' => $flip['status'],
                        'fee' => $flip['fee'],
                        'transaction_id' => $flip['id'],
                        'beneficiary_name' => $flip['beneficiary_name'],
                        'timestamp' => $flip['timestamp'],
                        'receipt' => $flip['receipt'],
                        'time_served' => $flip['time_served'] != '0000-00-00 00:00:00' ? 
                            $flip['time_served'] : null
                    ]
                )
            );
    
            DB::commit();

            return new DisbursmentResource($disbursment);
        } catch (\Throwable $th) {
            DB::rollBack();

            $error['data'] = null;
            $error['status'] = 500;
            $error['message'] = $th->getMessage();
            $error['errors'] = null;

            return response()->json($error, 500);
        }
    }
}
