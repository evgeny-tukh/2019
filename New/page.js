var loading = null;
var docker  = null;

function showProgressIndicator ()
{
    loading.style.display = null;
    loading.style.zOrder  = 500;
}

function hideProgressIndicator ()
{
    loading.style.display = 'none';
}

function redirectTo (url, logoutLink)
{
    showProgressIndicator ();

    if (docker)
        docker.src = url;
}

function commonPageInit ()
{
    loading = document.getElementById ('loading');
    docker = document.getElementById ('docker');

    hideProgressIndicator ();

    docker.onload = hideProgressIndicator;
}
