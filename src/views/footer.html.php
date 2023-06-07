<footer class="bg-light py-4 footer mt-auto">
    <div class="container">
        Mon footer
    </div>
</footer>


</body>
<script>
    const dropdown = document.getElementById("filterAttributes");

    dropdown.addEventListener("change", function(e) {

        // document.getElementById("filterAttributes").submit();
        e.target.submit();

    });
</script>

</html>