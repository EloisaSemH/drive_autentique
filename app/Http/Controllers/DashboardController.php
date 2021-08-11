<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filesInDir = GoogleDriveController::listFiles();
        $dirsInDirs = GoogleDriveController::listDirs();
        if ($request->has('dir')) {
            if ($dir = GoogleDriveController::findFolder($request->dir, false, $request->oldPath ?? '/')) {
                $oldPath = ($request->oldPath) ? $request->oldPath . '/' . $dir['path'] : $dir['path'];
                $filesInDir = GoogleDriveController::listFiles($dir['path']);
                $dirsInDirs = GoogleDriveController::listDirs($dir['path']);
            } else {
                $message = 'Ocorreu um erro ao acessar pasta';
            }
        }

        return view('screens.dashboard.index', [
            'filesInDir' => $filesInDir,
            'dirsInDir' => $dirsInDirs,
            'oldPath' => $oldPath ?? null,
            'goBack' => $request->goBack ?? null,
            'message' => $message ?? null,
        ]);
    }

    public function step(Request $request)
    {
        $filesInDir = GoogleDriveController::listFiles();
        $dirsInDirs = GoogleDriveController::listDirs();

//        dd($filesInDir, $dirsInDirs);
        return view('screens.dashboard.step', [
            'title' => 'Selecione a pasta que contém os pdfs',
            'filesInDir' => $filesInDir,
            'dirsInDir' => $dirsInDirs,
            'extension' => 'pdf',
            'route' => '',
            'filename' => $request->filename ?? '',
        ]);
    }

    public function fileToSend(Request $request)
    {
        $autentique = new AutentiqueController();
        if ($request->hasFile('excel')) {
            $excel = (new UsersImport)->toArray($request->file('excel'));
            $excel = $excel[0];
            array_shift($excel);

            foreach ($excel as $item) {
                $path = storage_path() . '\\app\\contratos\\';
                $name = $item[0] . '.pdf';
                $pathWithName = $path . $name;
                $email = $item[1];
                $autentique->create($name, $email, $pathWithName);
            }
            $message = 'Sucesso';
        } else {
            $name = $request->file('file')->getClientOriginalName();
            $email = $request->email;
            $archive = $request->file('file')->move(public_path() . '\\documents\\', $request->file('file')->getClientOriginalName());
            $pathWithName = public_path() . '\\documents\\' . $request->file('file')->getClientOriginalName();
            $message = $autentique->create($name, $email, $pathWithName);
        }
        return view('screens.dashboard.index', ['message' => $message]);
    }

    public function review(Request $request)
    {
        return view('screens.dashboard.review', [
            'title' => 'Revisão',
            'filename' => $request->filename ?? '',
            'directory' => $request->directory ?? '',
        ]);
    }

    public function send(Request $request)
    {
        if ($files = GoogleDriveController::listFolderContents($request->directory)) {
            $excel = GoogleDriveController::get($request->filename);
            $excel = (new UsersImport)->toArray($excel['target_file']);
            $excel = $excel[0];
            array_shift($excel);
            $autentique = new AutentiqueController();
            $dir = GoogleDriveController::findFolder($request->directory);
            foreach ($files as $name => $path) {
                foreach ($excel as $row) {
                    if ($row[0] . '.pdf' == $name) {
                        if (!is_null($row[0]) && !is_null($row[1])) {
                            $data = GoogleDriveController::get($name, $dir['path'], false, true, 'contracts',);
                            $autentique->create($row[0], $row[1], $data['target_file']);
                            File::delete($data['target_file']);
                        }
                    }
                }
            }
            return view('screens.dashboard.sent', [
                'title' => 'Sucesso!',
                'bgColor' => 'success',
                'message' => 'Os arquivos a serem assinados foram enviados corretamente!'
            ]);
        }
        return view('screens.dashboard.sent', [
            'title' => 'Ocorreu um erro :(',
            'bgColor' => 'danger',
            'message' => 'Ocorreu algum erro desconhecido',
        ]);
    }
}
