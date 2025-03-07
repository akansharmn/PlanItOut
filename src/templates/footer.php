
    </div>
</body>
</html>
</div>
    <script>
        // Initialize all MDC components
        document.querySelectorAll('.mdc-button, .mdc-icon-button, .mdc-card, .mdc-text-field').forEach(element => {
            if (element.classList.contains('mdc-button') || element.classList.contains('mdc-icon-button')) {
                mdc.ripple.MDCRipple.attachTo(element);
            }
            if (element.classList.contains('mdc-text-field')) {
                mdc.textField.MDCTextField.attachTo(element);
            }
        });
    </script>
</body>
</html>
