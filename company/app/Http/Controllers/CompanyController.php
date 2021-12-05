<?php

namespace App\Http\Controllers;

use App\Helpers\FileHelper;
use App\Helpers\ResponseFormatter;
use App\Models\Company;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('company.index', [
            'companies' => Company::paginate(5),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCompanyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompanyRequest $request)
    {
        try {
            $input = $request->validated();
            $file = FileHelper::store($request->file('logo'), 'companies');
            $input['logo'] = $file->id;
            $company = Company::create($input);
            return ResponseFormatter::success($company, 'Company created successfully');
        } catch (\Exception $e) {
            return ResponseFormatter::error(message: $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }
    public function edit(Company $company)
    {
        try {
            return ResponseFormatter::success($company, 'Company found successfully');
        } catch (\Throwable $th) {
            return ResponseFormatter::error(message: $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCompanyRequest  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        try {
            $input = $request->validated();
            if ($request->hasFile('logo')) {
                FileHelper::delete($company->file->path);
                $file = FileHelper::store($request->file('logo'), 'companies');
                $input['logo'] = $file->id;
            }else{
                $input['logo'] = $company->logo;
            }
            $company->update($input);
            return ResponseFormatter::success($company, 'Company updated successfully');
        } catch (\Exception $e) {
            return ResponseFormatter::error(message: $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        try {
            $company->delete();
            return ResponseFormatter::success($company, 'Company deleted successfully');
        } catch (\Exception $e) {
            return ResponseFormatter::error(message: $e->getMessage());
        }
    }
}
