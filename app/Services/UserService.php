<?php

namespace App\Services;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class UserService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
    }

    public function createUser(UserRequest $usersRequest)
    {
        $userFind = User::where('email', $usersRequest->email)->first();
        if (!$userFind) {
            $user = new User();
            $user->name = $usersRequest->name;
            $user->email = $usersRequest->email;
            $user->password = $usersRequest->password;
            $user->email_verified_at = Carbon::now();
            $user->role_id = 2;
            $user->save();
            return $user;
        }
        return null;
    }

    /**
     * @param Users $user
     * @return string
     */
    public function generatePassword(Users $user): string
    {
        $password = Str::password();

        $this->resetPasswordUser($user, $password);

        return $password;
    }

    public function checkPassword(string $passwordInto, $password): bool
    {
        return Hash::check($passwordInto, $password);
    }

    public function resetPasswordUser(User $user, string $newPassword): void
    {
        $user->password = Hash::make($newPassword);
        $user->save();
    }

    public function getUrl(string $token, string $email): string
    {
        return URL::temporarySignedRoute(
            'sent.email',
            now()->addMinutes(15),
            ['zx' => $token, 'rt' => base64_encode($email)]
        );
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_reset_tokens')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return response()->json([
                'message' => 'El token de recuperación de contraseña no es válido',
            ], 422);
        }

        User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();
        return response()->json([
            'message' => 'Contraseña actualizada con éxito',
        ], 200);
    }

    public function sentPasswordRestore(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $email = $request->email;
        $token = Str::random(64);
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['message' => 'El correo electrónico no existe'], 404);
        }
        $params = [
            'name' => $user->name,
            'url' => $this->getUrl($token, $email)
        ];

        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
        $variables = ['body' => 'Usuario ' . $params['name'] . ' Url para el restablecimiento de contraseña' . $params['url']];

        BaseService::sendEmail('emails.reset', $variables, env('MAIL_FROM_ADDRESS'), 'Find All', $email, '¡Bienvenido/a a FindAll');


        return response()->json([
            'message' => 'Se ha enviado un correo electrónico para recuperar la contraseña'
        ], 200);
    }
}
