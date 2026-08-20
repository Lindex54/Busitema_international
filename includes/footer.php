<footer class="site-footer">
    <div class="container">
        <p>&copy; <?= date('Y') ?> Busitema University Internationalization & Partnerships.</p>
    </div>
</footer>
<script src="<?= htmlspecialchars($assetBase ?? '/assets') ?>/js/main.js"></script>
<script src="<?= htmlspecialchars($assetBase ?? '/assets') ?>/js/navigation.js<?= isset($assetVersion) ? $assetVersion('js/navigation.js') : '' ?>"></script>
<script src="<?= htmlspecialchars($assetBase ?? '/assets') ?>/js/sliders.js"></script>
</body>
</html>
