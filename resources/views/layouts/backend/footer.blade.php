<footer class="footer">
    {{--
    <div class="footer-block buttons">
        <iframe class="footer-github-btn"
                src="https://ghbtns.com/github-btn.html?user=modularcode&repo=modular-admin-html&type=star&count=true"
                frameborder="0" scrolling="0" width="140px" height="20px"></iframe>
    </div>
    --}}
    <div class="footer-block author">
        <ul>
            {{--
            <li>
                created by
                <a href="https://github.com/modularcode">ModularCode</a>
            </li>
            --}}
            <li>
                <i class="fa fa-copyright"></i> Copyright {{ date("Y").' | '.config('constants.companyInfo.longName') }}. Todos los derechos reservados
            </li>
        </ul>
    </div>
</footer>