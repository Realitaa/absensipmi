<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Ambil data user dari sesi
        $userData = $session->get('user_data');
        $currentRole = $userData['Role'] ?? null;
        $previousURL = previous_url();

        // 1. Jika tidak ada role di session, redirect ke '/'
        if (!$currentRole) {
            return redirect()->to('/')->with('error', 'Sesi Anda tidak valid. Silakan login ulang.');
        }

        // 2. Jika role di session adalah 'karyawan' dan mencoba akses admin
        if ($currentRole === 'karyawan' && $arguments && in_array('admin', $arguments)) {
            return redirect()->to($previousURL)->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        // 3. Jika role di session adalah 'admin' dan mencoba akses karyawan
        if ($currentRole === 'admin' && $arguments && in_array('karyawan', $arguments)) {
            return redirect()->to($previousURL)->with('error', 'Anda tidak memiliki akses ke halaman karyawan.');
        }
    }
    
    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
