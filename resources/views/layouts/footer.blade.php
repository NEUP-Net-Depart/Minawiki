<footer class="page-footer theme">
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
                <h5 class="white-text">Footer Content</h5>
                <p class="grey-text text-lighten-4">You can use rows and columns here to organize your footer
                    content.</p>
            </div>
            <div class="col l4 offset-l2 s12">
                <h5 class="white-text">Links</h5>
                <ul>
                    <li><a class="grey-text text-lighten-3" href="#!">Link 1</a></li>
                    <li><a class="grey-text text-lighten-3" href="#!">Link 2</a></li>
                    <li><a class="grey-text text-lighten-3" href="#!">Link 3</a></li>
                    <li><a class="grey-text text-lighten-3" href="#!">Link 4</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-copyright theme-dark">
        <div class="container theme-lk">
            © {{ env('APP_NAME', "Minawiki") }} 由 {{ date('Y') }} {!! env('APP_PROVIDER', "<a href='https://github.com/NEUP-Net-Depart'>NEUP-Net-Department</a>") !!} 提供. 使用 <a href="https://github.com/hudson6666/Minawiki">Minawiki</a> v{{ env('APP_VER') }}.
            <a class="theme-lk right" href="#!">Made with love.</a>
        </div>
    </div>
</footer>