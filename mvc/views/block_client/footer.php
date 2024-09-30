<script>
    (function(d,t) {
    var BASE_URL="https://app.ahaspot.com";
    var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
    g.src=BASE_URL+"/packs/js/sdk.js";
    g.defer = true;
    g.async = true;
    s.parentNode.insertBefore(g,s);
    g.onload=function(){
        window.chatwootSDK.run({
        websiteToken: 'grivjQe7GdHdxDUB6YCwgarS',
        baseUrl: BASE_URL
        })
    }
    })(document,"script");
</script>
<footer class="py-3 my-4">
    <ul class="nav justify-content-center border-bottom pb-3 mb-3">
        <li class="nav-item"><a href="#" class="nav-link text-body-secondary" style="font-size: 14px;">Home</a>
        </li>
        <li class="nav-item"><a href="#" class="nav-link text-body-secondary" style="font-size: 14px;">Features</a>
        </li>
        <li class="nav-item"><a href="#" class="nav-link text-body-secondary" style="font-size: 14px;">Pricing</a>
        </li>
        <li class="nav-item"><a href="#" class="nav-link text-body-secondary" style="font-size: 14px;">FAQs</a>
        </li>
        <li class="nav-item"><a href="#" class="nav-link text-body-secondary" style="font-size: 14px;">About</a>
        </li>
    </ul>
    <p class="text-center text-body-secondary">&copy; 2024 Company, Inc</p>
</footer>
</body>

</html>