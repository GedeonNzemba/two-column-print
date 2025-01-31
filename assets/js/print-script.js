var TCP = {
    print: function() {
        // Create print iframe
        var iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        document.body.appendChild(iframe);
        
        var doc = iframe.contentWindow.document;
        
        // Add print styles
        var style = doc.createElement('style');
        style.textContent = `
            @media print {
                @page {
                    margin: 0.5cm !important;
                    size: auto;
                }
                @page :first {
                    margin-top: 0 !important;
                }
                @page :left {
                    margin-left: 0 !important;
                }
                @page :right {
                    margin-right: 0 !important;
                }
                body {
                    margin: 0;
                    padding: 20px;
                }
                .tcp-print-container {
                    display: flex;
                    justify-content: space-between;
                    width: 100%;
                }
                .tcp-left-column {
                    width: ${tcpOptions.left_width}%;
                }
                .tcp-right-column {
                    width: ${tcpOptions.right_width}%;
                }
                .tcp-middle-space {
                    width: ${tcpOptions.middle_space}%;
                }
                img {
                    max-width: 100%;
                    height: auto;
                }
                /* Ensure content doesn't overflow */
                * {
                    overflow-wrap: break-word;
                    word-wrap: break-word;
                    -ms-word-break: break-all;
                    word-break: break-word;
                }
                /* Hide all headers and footers */
                header, footer, .header, .footer,
                .site-header, .site-footer,
                #header, #footer,
                .wp-header, .wp-footer {
                    display: none !important;
                }
            }
        `;
        doc.head.appendChild(style);
        
        // Add meta tags to control printing
        var meta = doc.createElement('meta');
        meta.name = 'viewport';
        meta.content = 'width=device-width, initial-scale=1.0';
        doc.head.appendChild(meta);
        
        // Create content container
        var container = doc.createElement('div');
        container.className = 'tcp-print-container';
        
        // Left column
        var leftColumn = doc.createElement('div');
        leftColumn.className = 'tcp-left-column';
        var leftSelectors = tcpOptions.left_selectors.split(',').map(s => s.trim());
        leftSelectors.forEach(function(selector) {
            var elements = document.querySelectorAll(selector);
            elements.forEach(function(el) {
                leftColumn.appendChild(el.cloneNode(true));
            });
        });
        
        // Middle space
        var middleSpace = doc.createElement('div');
        middleSpace.className = 'tcp-middle-space';
        
        // Right column
        var rightColumn = doc.createElement('div');
        rightColumn.className = 'tcp-right-column';
        var rightSelectors = tcpOptions.right_selectors.split(',').map(s => s.trim());
        rightSelectors.forEach(function(selector) {
            var elements = document.querySelectorAll(selector);
            elements.forEach(function(el) {
                rightColumn.appendChild(el.cloneNode(true));
            });
        });
        
        // Append columns to container
        container.appendChild(leftColumn);
        container.appendChild(middleSpace);
        container.appendChild(rightColumn);
        
        // Add container to iframe
        doc.body.appendChild(container);
        
        // Set up print options
        var printOptions = {
            printBackground: true,
            headerTemplate: ' ',
            footerTemplate: ' ',
            displayHeaderFooter: false
        };
        
        // Print and cleanup
        setTimeout(function() {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
        }, 500);
        
        // Remove iframe after printing
        iframe.contentWindow.onafterprint = function() {
            document.body.removeChild(iframe);
        };
    }
};
