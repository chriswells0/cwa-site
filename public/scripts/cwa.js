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
		forms: {},
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
			identifier = jForm.attr("id") || jForm.attr("name"),
			initialState = jForm.serialize(),
			options = $.extend({
				ajax: "false",
				autoValidate: "true",
				protectChanges: "true",
				patterns: {
					date: /^(\d{4}-[01]\d-[0-3]\d)?$/,
					email: /^(("[\w-\s]+")|([\w\-]+(?:\.[\w\-]+)*)|("[\w-\s]+")([\w\-]+(?:\.[\w\-]+)*))(@((?:[\w\-]+\.)*\w[\w\-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i,
					tel: /^((\+\d)*\s*(\(\d{3}\)\s*)*\d{3}(-{0,1}|\s{0,1})\d{2}(-{0,1}|\s{0,1})\d{2})?$/
				}
			}, htmlForm.dataset, customOptions),
			Form = {
				$: jForm,
				elements: htmlForm.elements,
				addPatterns: function (newPatterns) {
					$.extend(options.patterns, newPatterns);
				},
				ajaxify: function (customSettings) {
					jForm.submit(function (e) {
						e.preventDefault();
						if (Form.getErrorCount() === 0) {
							var settings = $.extend({
								type: jForm.attr("method").toUpperCase(),
								url: jForm.attr("action"),
								data: jForm.serialize(),
								dataType: "json"
							}, customSettings);
							jForm.trigger("cwa-form-beforesubmit");
							$.ajax(settings).always(function (response) {
								if (response.status && response.status.code === 200 && response.data) {
									jForm.trigger("cwa-form-submit-success", response);
								} else {
									var responseJSON = response.responseJSON || { "status": { "message": "An unspecified error has occurred. Please try again." }};
									jForm.trigger("cwa-form-submit-failure", responseJSON);
								}
								jForm.trigger("cwa-form-submit-always", response);
							});
						}
					});
				},
				clearError: function (field) {
					var fieldID = (field.attr("id") || field.attr("name"));
					field.removeClass("error");
					$("label[for='" + fieldID + "']").removeClass("error");
					$("#" + fieldID + "-error").remove();
				},
				clearErrors: function () {
					jForm.find(".dynamic-error").remove();
					jForm.find("label.error").removeClass("error");
					jForm.find(":input").removeClass("error");
				},
				getErrorCount: function () {
					return jForm.find(".dynamic-error:visible").length;
				},
				hasChanged: function () {
					return (initialState !== jForm.serialize());
				},
				on: function () {
					jForm.on.apply(jForm, arguments);
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

					jForm.on("reset", function () {
						warning = null;
					});
				},
				reset: function () {
					htmlForm.reset();
				},
				setError: function (field, message) {
					var fieldID = (field.attr("id") || field.attr("name")),
						errorID = fieldID + "-error",
						label = $("label[for='" + fieldID + "']");
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

		if (options.autoValidate === "true") {
			jForm.submit(function (e) {
				if (!Form.validate()) {
					e.preventDefault();
				}
			});
		}

		if (options.ajax === "true") {
			Form.ajaxify();
		}

		if (options.protectChanges === "true") {
			Form.protectChanges();
		}

		if (identifier) { // Store for easy access. -- cwells
			CWA.DOM.forms[identifier] = Form;
		}
		return Form;
	};

	CWA.MVC = {
	};

	CWA.MVC.View = {
		activeModal: null,
		cancelEdit: function (e) {
			e.preventDefault();
			if (document.referrer.indexOf(document.location.protocol + "//" + document.location.hostname) === 0) {
				history.back(); // The referring page was on this site, so just go back. -- cwells
				// Fallback functionality for when the page was opened in a new tab (no history). -- cwells
				var self = this;
				window.setTimeout(function () { // Give time for history.back() to work. -- cwells
					document.location = self.dataset.destination || (CWA.MVC.ControllerURL + "/admin");
				}, 200);
			} else { // Go to the specified destination or the admin page for the current controller. -- cwells
				document.location = this.dataset.destination || (CWA.MVC.ControllerURL + "/admin");
			}
		},
		confirmDelete: function (e) {
			e.preventDefault();
			if (confirm("Are you sure you want to delete this item?")) {
				var href = $(this).attr("href"),
					itemID = href.substr(href.indexOf("/delete/") + 8),
					postData = { "itemID": itemID };
				postData[CWA.MVC.View.syncToken.name] = CWA.MVC.View.syncToken.value;
				$.ajax({
					type: "POST",
					url: href,
					data: postData,
					dataType: "json",
					complete: function (jqXHR) {
						var response = jqXHR.responseJSON,
							unknownError = "An error occurred while deleting the specified item.";
						if (!response || !response.status) {
							alert(unknownError);
						} else if (response.status.code !== 200) {
							alert(response.status.message || unknownError);
						} else { // The item was deleted. -- cwells
							if (response.data && response.data.NextURL) {
								window.location.href = response.data.NextURL;
							} else if (response.data && response.data.ControllerURL) {
								window.location.href = response.data.ControllerURL;
							} else {
								alert("Successfully deleted the specified item.");
							}
						}
					}
				});
			}
		},
		createSlug: function (str, allowUppercase) {
			if (typeof str !== "string") { return ""; }

			if (!allowUppercase) {
				str = str.toLowerCase();
			}
			str = str.replace(/'/g, ""); // Remove apostrophes.
			str = str.replace(/&/g, "and");
			// Allowed characters per RFC 1738:  alphanumeric and $-_.+!*'(),
			/*jslint regexp: true */
			str = str.replace(/[^a-z0-9$\-_.+!*()]/gi, "-"); // Replace all characters except our whitelist with hyphen.
			str = str.replace(/^[^a-z0-9]*|[^a-z0-9]*$/gi, ""); // Remove non-alphanumeric from the beginning and end.
			str = str.replace(/--+/g, "-"); // Replace multiple hyphens with one hyphen.
			/*jslint regexp: false */

			return str;
		},
		loadInModal: function (e) {
			e.preventDefault();
			var modal = new CWA.MVC.View.Modal(),
				url = $(this).attr("href");
			modal.load(url + (url.indexOf("?") === -1 ? "?" : "&") + "partial=true");
		},
		on: function (eventName, method) {
			$(document).bind(eventName, method);
		},
		suggestSlug: function (e) {
			e.preventDefault();
			// Only suggest a new slug when the destination is empty. -- cwells
			if ($("#" + this.dataset.to).val() === "") {
				CWA.MVC.View.updateSlug.call(this, e);
			}
		},
		updateSlug: function (e) {
			e.preventDefault();
			var from = $("#" + this.dataset.from),
				to = $("#" + this.dataset.to);
			to.val(CWA.MVC.View.createSlug(from.val(), this.dataset.allowUppercase));
		}
	};

	CWA.MVC.View.Modal = function () {
		// Private variables and the main object, which is returned as a public instance:
		var jElement = $("#modal"),
			modalBusy = $("#modal-busy"),
			modalContainer = $("#modal-container"),
			modalContent = $("#modal-content"),
			Modal = {
				$: jElement,
				close: function () {
					CWA.MVC.View.activeModal = null;
					this.showBusy();

					jElement.fadeOut(function () {
						jElement.trigger("cwa-modal-closed");
						jElement.remove();
					});
				},
				load: function (url) {
					// Add the modal element to the DOM if it does not already exist. -- cwells
					if (jElement.length === 0) {
						jElement = $("<div />", { id: "modal" }).appendTo($("#content-wrapper"));
						modalContainer = $("<div />", { id: "modal-container" }).appendTo(jElement);
						modalBusy = $("<div />", {
							id: "modal-busy",
							class: "content loading",
							html: '<img src="/images/loading.gif" />'
						}).appendTo(modalContainer);
						modalContent = $("<div />", { id: "modal-content" }).appendTo(modalContainer);
					}

					CWA.MVC.View.activeModal = this;
					this.showBusy();

					jElement.fadeIn(function () {
						$.get(url, function (response) {
							self.setContent(response);
							var forms = jElement.find("form");
							if (forms.length !== 0) {
								forms.each(function (index) {
									if (this.dataset.autoinit !== "false") {
										var form = CWA.DOM.Form(this, { ajax: "true" });
										form.on("cwa-form-beforesubmit", function (e) {
											self.showBusy();
											$("#modal-error").remove();
										});
										form.on("cwa-form-submit-failure", function (e, response) {
											var error = $("<div />", {
												id: "modal-error",
												class: "error"
											}).prependTo(form.$);
											error.html(response.status.message);
											self.showContent();
										});
										form.on("cwa-form-submit-success", function (e) {
											self.close();
										});
									}
								});
							}
							jElement.find("button[data-cwa-click='cancelEdit']").off("click").on("click", function (e) {
								e.stopPropagation();
								self.close();
							});
							self.showContent(); // Content must be visible in order to set focus to an element. -- cwells
							jElement.find("[autofocus]").focus();
							jElement.trigger("cwa-modal-loaded");
						});
					});
				},
				setContent: function (content) {
					modalContent.html(content);
				},
				showBusy: function () {
					modalContent.hide();
					modalBusy.show();
				},
				showContent: function () {
					modalBusy.hide();
					modalContent.show();
				}
			},
			self = Modal;

		return Modal;
	};

	$(document).ready(function () {
		$("#content-wrapper").on("click", "[data-cwa-click]", function (e) {
			if (typeof CWA.MVC.View[this.dataset.cwaClick] === "function") {
				CWA.MVC.View[this.dataset.cwaClick].call(this, e);
			}
		});
		$("#content-wrapper").on("focus", "[data-cwa-focus]", function (e) {
			if (typeof CWA.MVC.View[this.dataset.cwaFocus] === "function") {
				CWA.MVC.View[this.dataset.cwaFocus].call(this, e);
			}
		});
	});

}());
