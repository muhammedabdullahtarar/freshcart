function checkAuth() {
    const token = localStorage.getItem('token');
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    const currentPath = window.location.pathname;

    if (token && user.id) {
        if (user.type === 'admin' || user.type === 'super_admin') {
            return;
        } else {
            if (currentPath !== '/') {
                window.location.href = '/';
            }
        }
    } else {
        if (currentPath !== '/') {
            window.location.href = '/';
        }
    }
}
