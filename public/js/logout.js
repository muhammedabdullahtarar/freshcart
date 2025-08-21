function logout() {
    const token = localStorage.getItem('token');
 
    fetch('/api/logout', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': token ? `Bearer ${token}` : ''
        }
    })
    .then(response => response.json())
    .then(data => {
      
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        
     
        window.location.href = '/signin';
    })
    .catch(error => {
        console.error('Logout error:', error);
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        window.location.href = '/signin';
    });
}
