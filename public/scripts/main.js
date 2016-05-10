/*
 * Copyright (c) 2014 Chris Wells (https://chriswells.io)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */

/*jslint plusplus: true, white: true */
/*global document, $, CWA, analyticsID */

$(document).ready(function () {
	"use strict";

	var i;

	// Enable Google Analytics in prod. -- cwells
	if (analyticsID !== "") {
		// Ignore jslint errors in Google's code. -- cwells
		/*jshint ignore:start*/
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', analyticsID, 'auto');
		ga('send', 'pageview');
		/*jshint ignore:end*/
	}

	// Protect changes in all forms except when data-autoinit is false. -- cwells
	for (i = 0; i < document.forms.length; i++) {
		if (document.forms[i].dataset.autoinit !== "false") {
			CWA.DOM.Form(document.forms[i]);
		}
	}

	// Confirm deletions before proceeding. -- cwells
	$("a.delete").click(CWA.MVC.View.confirmDelete);

	// Force external links to open in a new window. -- cwells
	$("a[rel*='external']").each(function () {
		var link = $(this),
			title = link.attr("title") || "";
		link.attr("target", "_blank");
		link.attr("title", (title === "" ? "" : title + " ") + "(opens in new window)");
	});

	// Clearly label all sponsored links. -- cwells
	$("a[data-sponsored='true']").each(function () {
		var link = $(this),
			title = link.attr("title") || "";
		link.attr("title", (title === "" ? "" : title + "\n") + "Sponsored links help support this site.");
	});

	// Load TinyMCE if needed. -- cwells
	if ($("textarea[data-html-editor='true']").length !== 0) {
		$.getScript("//cdn.tinymce.com/4/tinymce.min.js", function () {
			tinyMCE.init({
				body_class: "content",
				body_id: "wrapper",
				browser_spellcheck: true,
				content_css: "/styles/all.min.css?nocache=" + Math.random(),
				convert_urls: false,
				importcss_append: true,
				importcss_groups: [{title: "Custom Styles"}],
				plugins: "code fullscreen image importcss link preview table wordcount",
				rel_list: [
					{ title: "None", value: "" },
					{ title: "External", value: "external" },
					{ title: "No Follow", value: "nofollow" },
					{ title: "External and No Follow", value: "external nofollow" }
				],
				selector: "textarea[data-html-editor='true']",
				setup: function (editor) {
					editor.on("change", function (e) {
						tinyMCE.triggerSave();
						$(tinyMCE.activeEditor.targetElm).trigger("input");
					});
				}
			});
		});
	}
});
