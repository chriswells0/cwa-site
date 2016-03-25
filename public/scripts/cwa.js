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
/*jshint laxbreak: true */
/*global window, document, confirm, $, CWA */

(function () {
	"use strict";

	window.CWA = window.CWA || {};

	CWA.DOM = {
		includeScript: function (filePath, async) {
			var regexFileName = new RegExp(filePath + "$"),
				bIsIncluded = false,
				arrScripts = document.getElementsByTagName("script"),
				i,
				elNewScript;
			for (i = 0; i < arrScripts.length; i++) {
				if (regexFileName.test(arrScripts[i].src)) {
					bIsIncluded = true;
					break;
				}
			}
			if (!bIsIncluded) {
				elNewScript = document.createElement("script");
				elNewScript.async = async;
				elNewScript.src = filePath;
				document.getElementsByTagName("head")[0].appendChild(elNewScript);
			}
		}
	};

	CWA.DOM.Form = function (htmlForm, customOptions) {
		// Private variables and the main object, which is returned as a public instance:
		var jForm = $(htmlForm),
			options = $.extend({
				autoValidate: true,
				protectChanges: true,
				patterns: {
					date: /^(\d{4}-[01]\d-[0-3]\d)?$/,
					email: /^(("[\w-\s]+")|([\w\-]+(?:\.[\w\-]+)*)|("[\w-\s]+")([\w\-]+(?:\.[\w\-]+)*))(@((?:[\w\-]+\.)*\w[\w\-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i,
					tel: /^((\+\d)*\s*(\(\d{3}\)\s*)*\d{3}(-{0,1}|\s{0,1})\d{2}(-{0,1}|\s{0,1})\d{2})?$/
				}
			}, customOptions),
			Form = {
				addPatterns: function (newPatterns) {
					$.extend(options.patterns, newPatterns);
				},
				clearError: function (field) {
					field.removeClass("error");
					$("label[for='" + field.attr("id") + "']").removeClass("error");
					$("#" + field.attr("id") + "-error").remove();
				},
				clearErrors: function () {
					jForm.find(".dynamic-error").remove();
					jForm.find("label.error").removeClass("error");
					jForm.find(":input").removeClass("error");
				},
				getErrorCount: function () {
					return jForm.find(".dynamic-error:visible").length;
				},
				protectChanges: function (fields, submit, warning) {
					fields = fields || "input:not(:button,:submit), select, textarea";
					submit = submit || "button[type='submit'], input[type='submit']";
					warning = warning || "You have unsaved changes on this page!";

					// ???: Should I only clear the warning if the form validates? -- cwells
					jForm.find(submit).click(function () {
						warning = null;
					});

					jForm.find(fields).bind("change input", function () {
						$(window).bind("beforeunload", function (e) {
							if (warning !== null) {
								(e || window.event).returnValue = warning;
								return warning;
							}
						});
					});
				},
				setError: function (field, message) {
					var errorID = field.attr("id") + "-error",
						label = $("label[for='" + field.attr("id") + "']");
					field.addClass("error");
					label.addClass("error");
					$("#" + errorID).remove();
					$("<div />", {
						id: errorID,
						class: "error dynamic-error",
						html: (message[0] === " " ? (label.text() + message) : message)
					}).insertAfter(field);
				},
				setPatterns: function (newPatterns) {
					options.patterns = newPatterns || {};
				},
				validate: function () {
					var fields = jForm.find(":input"),
						field,
						i;
					this.clearErrors();

					for (i = 0; i < fields.length; i++) {
						field = $(fields[i]);
						if (field.not(":hidden")) {
							if (field.attr("data-must-match")
									&& field.val() !== $("#" + field.attr("data-must-match")).val()) {
								this.setError(field, " must match " + $("label[for='" + field.attr("data-must-match") + "']").text() + ".");
							} else if (field.attr("required") && field.val().length === 0) {
								this.setError(field, " is required.");
							} else if (field.attr("minlength") && field.val().length < +field.attr("minlength")) {
								this.setError(field, " must be at least " + field.attr("minlength") + " characters long.");
							} else if (field.attr("maxlength") && field.val().length > +field.attr("maxlength")) {
								this.setError(field, " may not contain more than " + field.attr("maxlength") + " characters.");
							} else if (field.attr("pattern") && !(new RegExp("^" + field.attr("pattern") + "$")).test(field.val())) {
								this.setError(field, " is not valid.");
							} else if (!field.attr("pattern") && options.patterns[field.attr("type")]
									&& !options.patterns[field.attr("type")].test(field.val())) {
								this.setError(field, " is not valid.");
							}
						}
					}

					return (this.getErrorCount() === 0);
				}
			};

		if (options.autoValidate) {
			jForm.submit(function (e) {
				if (!Form.validate()) {
					e.preventDefault();
				}
			});
		}

		if (options.protectChanges) {
			Form.protectChanges();
		}

		return Form;
	};

	CWA.MVC = {
	};

	CWA.MVC.View = {
		confirmDelete: function (e) {
			e.preventDefault();
			if (confirm("Are you sure you want to delete this item?")) {
				var href = $(this).attr("href"),
					itemID = href.substr(href.lastIndexOf("/") + 1),
					postData = { "itemID": itemID };
				postData[CWA.MVC.View.syncToken.name] = CWA.MVC.View.syncToken.value;
				$.post(href, postData, function (response) {
					if (response && response.data && response.data.ControllerURL) {
						window.location.href = response.data.ControllerURL + "/admin";
					}
				}, "json");
			}
		},
		createSlug: function (str) {
			if (typeof str !== "string") { return ""; }

			str = str.toLowerCase().replace(/'/g, ""); // Convert to lowercase and remove apostrophes.
			str = str.replace(/&/g, "and");
			// Allowed characters per RFC 1738:  alphanumeric and $-_.+!*'(),
			/*jslint regexp: true */
			str = str.replace(/[^a-z0-9$\-_.+!*()]/g, "-"); // Replace all characters except our whitelist with hyphen.
			str = str.replace(/^[^a-z0-9]*|[^a-z0-9]*$/g, ""); // Remove non-alphanumeric from the beginning and end.
			str = str.replace(/--+/g, "-"); // Replace multiple hyphens with one hyphen.
			/*jslint regexp: false */

			return str;
		}
	};
}());
