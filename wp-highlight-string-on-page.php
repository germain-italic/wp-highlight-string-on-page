<?php
/*
Plugin Name: Highlight Search On Page
Description: Adds a search box to highlight text matches on the page.
Version: 1.0
Author: Germain-Italic
*/

function highlight_search_shortcode() {
    ob_start();
    ?>
    <input type="text" id="wphsop-searchInput" placeholder="Enter text to search">
    <button id="wphsop-searchButton">Search</button>

    <script>
    document.getElementById('wphsop-searchButton').addEventListener('click', function() {
        var searchValue = document.getElementById('wphsop-searchInput').value;
        if (searchValue) {
            removeHighlights();
            highlightText(searchValue);
        }
    });

    function highlightText(searchValue) {
        var regex = new RegExp(searchValue, 'gi');
        var walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, null, false);
        var node;
        var matches = [];

        // Collect all matches first
        while (node = walker.nextNode()) {
            if (node.parentNode && node.parentNode.nodeName !== 'SCRIPT' && node.parentNode.nodeName !== 'STYLE') {
                if (regex.test(node.nodeValue)) {
                    matches.push(node);
                }
            }
        }

        // Now highlight the collected matches
        matches.forEach(function(node) {
            var parent = node.parentNode;
            var span = document.createElement('span');
            span.innerHTML = node.nodeValue.replace(regex, function(matched) {
                return '<mark>' + matched + '</mark>';
            });
            parent.replaceChild(span, node);
        });
    }

    function removeHighlights() {
        var marks = document.querySelectorAll('mark');
        marks.forEach(function(mark) {
            var parent = mark.parentNode;
            parent.replaceChild(document.createTextNode(mark.textContent), mark);
            parent.normalize();
        });
    }
    </script>

    <style>
    mark {
        background-color: yellow;
    }
    </style>
    <?php
    return ob_get_clean();
}

add_shortcode('highlight_search', 'highlight_search_shortcode');
