<?php

namespace App\Http\Controllers;

use App\Exports\MasterItemsExport;
use App\Models\CategoryItem;
use App\Models\MasterItem;
use App\Models\MasterItemCategories;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;

        $data_search = MasterItem::query();

        if (!empty($kode)) $data_search = $data_search->where('kode', $kode);
        if (!empty($nama)) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        if (!empty($hargamin) && !empty($hargamax) ) $data_search = $data_search->where('harga_beli', '>=', $hargamin)->where('harga_beli', '<=', $hargamax);

        $data_search = $data_search->select('kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier')->orderBy('id')->get();


        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = [];
        } else {
            $item = MasterItem::find($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;
        $data['categories'] =  CategoryItem::orderBy('nama')->get();
        $data['selectedCategoryIds'] = $data['categories']->pluck('id')->toArray();

        
        return view('master_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = MasterItem::where('kode', $kode)->first();

        $data['categories'] = MasterItemCategories::leftJoin('category_items', 'master_item_categories.category_id', '=', 'category_items.id')
        ->where('master_item_categories.master_item_id', $data['data']->id)
        ->select(
            'master_item_categories.*',
            'category_items.kode as category_kode',
            'category_items.nama as category_nama'
        )
        ->get();

        

        return view('master_items.single.index', $data);
    }

    public function generatePdf($kode)
{
    $data['data'] = MasterItem::where('kode', $kode)->firstOrFail();
    
    $data['categories'] = MasterItemCategories::leftJoin('category_items', 'master_item_categories.category_id', '=', 'category_items.id')
        ->where('master_item_categories.master_item_id', $data['data']->id)
        ->select(
            'master_item_categories.*',
            'category_items.kode as category_kode',
            'category_items.nama as category_nama'
        )
        ->get();
    
    // Generate timestamp
    $data['timestamp'] = now()->format('d-m-Y H:i:s');
    
    // Load view and generate PDF
    $pdf = Pdf::loadView('master_items.single.pdf', $data);
    
    // Set paper size and orientation
    $pdf->setPaper('A4', 'portrait');
    
    // Download or stream
    return $pdf->stream('Item_' . $kode . '_' . now()->format('Ymd_His') . '.pdf');
    // Or use download() to force download:
    // return $pdf->download('Item_' . $kode . '.pdf');
}

    public function formSubmit(Request $request, $method, $id = 0)
    {
        // Validate the request
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga_beli' => 'required|numeric',
            'laba' => 'required|numeric',
            'supplier' => 'required|string',
            'jenis' => 'required|string',
            'category_ids' => 'array',
            'category_ids.*' => 'exists:category_items,id',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
        ]);


        if ($method == 'new') {
            $data_item = new MasterItem;
            $kode = MasterItem::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
            sleep(3);
        } else {
            $data_item = MasterItem::find($id);
            $kode = $data_item->kode;
        }

        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;


        // Handle photo upload
        if ($request->hasFile('foto')) {
            // Delete old photo if exists (for edit mode)
            if ($method == 'edit' && $data_item->foto) {
                Storage::disk('public')->delete($data_item->foto);
            }

            // Store new photo
            $path = $request->file('foto')->store('items', 'public');
            $data_item->foto = $path;
        }


        $data_item->save();

        // Handle categories
    if (!empty($request->input('category_ids')) && is_array($request->input('category_ids'))) {
        // Delete old categories for this item (for edit mode)
        if ($method == 'edit') {
            MasterItemCategories::where('master_item_id', $data_item->id)->delete();
        }

        // Insert new categories
        foreach ($request->input('category_ids') as $category_id) {
            MasterItemCategories::create([
                'master_item_id' => $data_item->id,
                'category_id' => $category_id,
            ]);
        }
    }

        return redirect('master-items')->with('success', 'Data berhasil disimpan!');
    }

    public function delete($id)
    {
        MasterItem::find($id)->delete();
        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach($data as $item)
        {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100,1000000);
            $item->laba = rand(10,99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi','Bukulapuk','TokoBagas','E Commurz','Blublu'];
        $random = rand(0,4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat','Alkes','Matkes','Umum','ATK'];
        $random = rand(0,4);
        return $array[$random];
    }




/**
 * Export Master Items to Excel
 */
public function exportExcel( Request $request )
{
    $fileName = 'Master_Items_' . now()->format('Y-m-d_His') . '.xlsx';
    
    return Excel::download(
        new MasterItemsExport($request->all()),
        $fileName
    );
}
}
