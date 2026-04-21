public function canAccessPanel(Panel $panel): bool
{
    $adminEmails = [
        'admin@uin.ar-raniry.ac.id',
        'email_admin2@uin.ar-raniry.ac.id',
        // tambahkan email admin lainnya di sini
    ];
    
    return in_array($this->email, $adminEmails);
}