<?php 

namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\GroupModel;
  

class SigninController extends Controller
{
    public function index()
    {   
        helper(['form']);
        echo view('signin');
    } 
  
public function loginAuth()
{
    $session = session();

    $userModel  = new UserModel();
    $groupModel = new GroupModel();

    $username = (string) $this->request->getVar('username');
    $password = (string) $this->request->getVar('password');

    $data = $userModel->where('username', $username)->first();

    if (! $data) {
        $session->setFlashdata('msg', 'Username does not exist.');
        return redirect()->to('/signin');
    }

    $group = $groupModel->where('id', $data['group_id'])->first();
    $usesSubGroups = ! empty($group) ? (bool) ($group['uses_sub_groups'] ?? false) : false;

    if (! password_verify($password, $data['password'])) {
        $session->setFlashdata('msg', 'Password is incorrect.');
        return redirect()->to('/signin');
    }

    // Build session data
    $ses_data = [
        'id'            => $data['id'],
        'name'          => $data['name'],
        'email'         => $data['user_email'],
        'is_admin'      => $data['is_admin'],
        'is_hotelcheck' => $data['is_hotelcheck'],
        'group_id'      => $data['group_id'],
        'account_id'    => $data['account_id'],
        'isLoggedIn'    => true,
        'enable_groups' => $usesSubGroups,
    ];

    $session->set($ses_data);

    // ---- Safe "return to" logic (replaces getallheaders()['Referer'] parsing) ----
    $returnTo = null;

    $referer = $this->request->getHeaderLine('Referer'); // may be empty
    if ($referer !== '') {
        $refUri = new \CodeIgniter\HTTP\URI($referer);
        $q = $refUri->getQuery(['only' => ['refer']]); // returns array
        $candidate = $q['refer'] ?? null;

        if (is_string($candidate) && $candidate !== '') {
            // Decode once (handles %2F etc)
            $candidate = rawurldecode($candidate);

            // Only allow internal redirects to prevent open redirect attacks
            // Accept "/path" or "path" and normalize to "/path"
            if (str_starts_with($candidate, '/')) {
                $returnTo = $candidate;
            } elseif (! preg_match('~^[a-zA-Z][a-zA-Z0-9+\-.]*://~', $candidate)) {
                $returnTo = '/' . ltrim($candidate, '/');
            }
        }
    }

    return redirect()->to($returnTo ?: '/audits');
}

    
    public function signout() {
         $session = session();
         $session->destroy();
         return redirect()->to('/signin');
    }
}