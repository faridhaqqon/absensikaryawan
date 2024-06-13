<?php

namespace App\Http\Controllers;
use App\Models\karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::query();
        $query->select('karyawan.*', 'nama_dept');
        $query->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept');
        if (!empty($request->nama_karyawan)){
            $query->where('nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }

        if (!empty($request->kode_dept)){
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }
        $karyawan = $query->paginate(10);

        $departemen = DB::table('departemen')->get();
        return view('karyawan.index', compact('karyawan', 'departemen'));
    }

    public function store(Request $request)
    {
        $nip =$request->nip;
        $nama_lengkap =$request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $kode_dept = $request->kode_dept;
        $password = Hash::make('123');

        if($request->hasFile('foto')) {
            $foto = $nip . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = null;
        }

        try {
            $data = [
                'nip' => $nip,
                'nama_lengkap' =>$nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'kode_dept' => $kode_dept,
                'foto' => $foto,
                'password' => $password

            ];
            $simpan = DB::table('karyawan')->insert($data);
            if ($simpan) {
                if ($request->hasfile('foto')) {
                    $folderPath = "public/uploads/karyawan";
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success'=> 'Data Berhasil Ditambahkan']);
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                $message = " NIP " . $nip . " Sudah Terdaftar Sebelumnya";
            }
            return Redirect::back()->with(['warning' => 'Gagal Menambahkan Data!!' .$message]);
        }
    }

    public function edit(Request $request)
    {
        $nip = $request->nip;
        $departemen = DB::table('departemen')->get();
        $karyawan = DB::table('karyawan')->where('nip', $nip)->first();
        return view('karyawan.edit' , compact('departemen', 'karyawan'));
    }

    public function update($nip, Request $request){
        $nip =$request->nip;
        $nama_lengkap =$request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $kode_dept = $request->kode_dept;
        $password = Hash::make('123');
        $old_foto = $request->old_foto;

        if($request->hasFile('foto')) {
            $foto = $nip . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $old_foto;
        }

        try {
            $data = [
                'nama_lengkap' =>$nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'kode_dept' => $kode_dept,
                'foto' => $foto,
                'password' => $password

            ];
            $update = DB::table('karyawan')->where('nip', $nip)->update($data);
            if ($update) {
                if ($request->hasfile('foto')) {
                    $folderPath = "public/uploads/karyawan";
                    $folderPathhOld = "public/uploads/karyawan" . $old_foto;
                    Storage::delete($folderPathhOld);
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success'=> 'Data Berhasil Update']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Update']);
        }
    }
        public function delete($nip){
            $delete = DB::table('karyawan')->where('nip', $nip)->delete();
            if($delete){
                return Redirect::back()->with(['success'=>'Data Berhasil Dihapus']);
            }else {
                return Redirect::back()->with(['warning'=>'Data Gagal Dihapus']);
            }
        }
    }

