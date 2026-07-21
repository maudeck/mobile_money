/* =========================================================================
   MOBILE MONEY — script.js
   Comportements partagés : sidebar admin, alertes, confirmation de
   suppression, calcul de frais en direct (retrait / transfert).
   ========================================================================= */
(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    initSidebar();
    initAlerts();
    initConfirmForms();
    initFraisCalculator("retrait-form", "frais-retrait-url");
    initFraisCalculator("transfert-form", "frais-transfert-url");
    initFraisCalculator("operation-form");
    initToggleMultiTransfert();
    initTelValidation();
  });

  /* ---------------------------------------------------------------------
     Sidebar admin (mobile)
     --------------------------------------------------------------------- */
  function initSidebar() {
    var toggle = document.querySelector("[data-sidebar-toggle]");
    var sidebar = document.querySelector(".sidebar");
    var backdrop = document.querySelector(".sidebar-backdrop");
    if (!toggle || !sidebar) return;

    function close() {
      sidebar.classList.remove("is-open");
      if (backdrop) backdrop.classList.remove("is-open");
    }
    function open() {
      sidebar.classList.add("is-open");
      if (backdrop) backdrop.classList.add("is-open");
    }

    toggle.addEventListener("click", function () {
      sidebar.classList.contains("is-open") ? close() : open();
    });
    if (backdrop) backdrop.addEventListener("click", close);
  }

  /* ---------------------------------------------------------------------
     Alertes : disparition automatique en douceur
     --------------------------------------------------------------------- */
  function initAlerts() {
    var alerts = document.querySelectorAll(".alert[data-autodismiss]");
    alerts.forEach(function (el) {
      setTimeout(function () {
        el.style.transition =
          "opacity .35s ease, transform .35s ease, margin .35s ease, padding .35s ease, max-height .35s ease";
        el.style.opacity = "0";
        el.style.transform = "translateY(-6px)";
        setTimeout(function () {
          el.remove();
        }, 380);
      }, 4500);
    });
  }

  /* ---------------------------------------------------------------------
     Confirmation avant suppression (data-confirm="message")
     --------------------------------------------------------------------- */
  function initConfirmForms() {
    document.querySelectorAll("form[data-confirm]").forEach(function (form) {
      form.addEventListener("submit", function (e) {
        var msg =
          form.getAttribute("data-confirm") || "Confirmer cette action ?";
        if (!window.confirm(msg)) e.preventDefault();
      });
    });
  }

  /* ---------------------------------------------------------------------
      Validation des numéros de téléphone : exactement 10 chiffres
      --------------------------------------------------------------------- */
  function initTelValidation() {
    var selectors = [
      "#telephone",
      "#beneficiaire",
      ".beneficiaire-input"
    ];
    selectors.forEach(function (sel) {
      document.querySelectorAll(sel).forEach(function (input) {
        if (input.dataset.telValidationAttached) return;
        input.dataset.telValidationAttached = "true";
        input.addEventListener("input", function () {
          var tel = this.value.trim();
          var valid = /^\d{10}$/.test(tel);
          if (tel && !valid) {
            this.classList.add("is-error");
            this.style.borderColor = "#b3261e";
            this.style.backgroundColor = "#fbeae9";
            this.style.color = "#b3261e";
          } else {
            this.classList.remove("is-error");
            this.style.borderColor = "";
            this.style.backgroundColor = "";
            this.style.color = "";
          }
        });
      });
    });

    document.querySelectorAll("form").forEach(function (form) {
      if (form.dataset.telValidationFormAttached) return;
      form.dataset.telValidationFormAttached = "true";
      form.addEventListener("submit", function (e) {
        var telInputs = form.querySelectorAll("#telephone, #beneficiaire, .beneficiaire-input");
        var hasInvalid = false;
        telInputs.forEach(function (input) {
          var tel = input.value.trim();
          if (tel && !/^\d{10}$/.test(tel)) {
            hasInvalid = true;
            input.classList.add("is-error");
            input.style.borderColor = "#b3261e";
            input.style.backgroundColor = "#fbeae9";
            input.style.color = "#b3261e";
          }
        });
        if (hasInvalid) {
          e.preventDefault();
          alert("Le numéro de téléphone doit contenir exactement 10 chiffres.");
        }
      });
    });
  }

  /* ---------------------------------------------------------------------
      Calcul de frais en direct pour retrait.php / transfert.php
      Les formulaires portent : #retrait-form ou #transfert-form
      avec des attributs data-frais-url / data-submit-url sur le <form>.
      --------------------------------------------------------------------- */
  function initFraisCalculator(formId) {
    var form = document.getElementById(formId);
    if (!form) return;

    var montantInput = form.querySelector("#montant");
    var feeDisplay = form.querySelector("#frais-display");
    var submitBtn = form.querySelector("button[type=submit]");
    var beneficiaireInput = form.querySelector("#beneficiaire");
    var ajouterFraisRetraitCheckbox = form.querySelector("#ajouter-frais-retrait");
    var fraisUrl = form.getAttribute("data-frais-url");
    var submitUrl = form.getAttribute("data-submit-url");
    var csrfName = form.getAttribute("data-csrf-name");
    var csrfHash = form.getAttribute("data-csrf-hash");
    var currentFrais = null;
    var currentFraisOption = 0;
    var currentFraisRetrait = 0;

    if (!montantInput || !feeDisplay || !fraisUrl) return;

    function setFee(text, isError) {
      feeDisplay.textContent = text;
      feeDisplay.classList.toggle("is-error", !!isError);
    }

    function getBeneficiaire() {
      return beneficiaireInput ? beneficiaireInput.value.trim() : "";
    }

    montantInput.addEventListener("input", function () {
      var montant = this.value;
      if (!montant) {
        setFee("Frais : —", false);
        currentFrais = null;
        return;
      }
      calculerFrais();
    });

    if (beneficiaireInput && ajouterFraisRetraitCheckbox) {
      beneficiaireInput.addEventListener("input", function () {
        var tel = beneficiaireInput.value.trim();
        var valid = /^\d{10}$/.test(tel);
        if (tel && (!valid || tel.substring(0, 3) !== "034")) {
          ajouterFraisRetraitCheckbox.disabled = true;
          ajouterFraisRetraitCheckbox.checked = false;
        } else {
          ajouterFraisRetraitCheckbox.disabled = false;
        }
      });
    }

    if (ajouterFraisRetraitCheckbox) {
      ajouterFraisRetraitCheckbox.addEventListener("change", function () {
        if (montantInput.value) {
          calculerFrais();
        }
      });
    }

    function calculerFrais() {
      var montant = montantInput.value;
      if (!montant) {
        setFee("Frais : —", false);
        currentFrais = null;
        currentFraisOption = 0;
        currentFraisRetrait = 0;
        return;
      }

      var body =
        "montant=" +
        encodeURIComponent(montant) +
        (beneficiaireInput && getBeneficiaire()
          ? "&beneficiaire=" + encodeURIComponent(getBeneficiaire())
          : "") +
        "&ajouter_frais_retrait=" +
        (ajouterFraisRetraitCheckbox && ajouterFraisRetraitCheckbox.checked ? "1" : "0") +
        "&" +
        csrfName +
        "=" +
        csrfHash;

      fetch(fraisUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: body,
      })
        .then(function (r) {
          return r.text().then(function (text) {
            try {
              var data = JSON.parse(text);
              if (!r.ok) {
                var msg = data && data.error ? data.error : ("Erreur HTTP " + r.status + " " + r.statusText);
                throw new Error(msg);
              }
              return data;
            } catch (e) {
              throw new Error("Réponse invalide (status=" + r.status + "): " + text.substring(0, 200));
            }
          });
        })
        .then(function (data) {
          if (data && data.error) {
            setFee(data.error, true);
            currentFrais = null;
            currentFraisOption = 0;
            currentFraisRetrait = 0;
          } else if (data && typeof data.frais !== 'undefined') {
            var feeText = "Frais : " + data.frais + " Ar";
            if (data.commission_valeur && data.commission_valeur > 0) {
              feeText += " + " + data.commission_valeur + " Ar (commission)";
            }
            if (data.frais_option && data.frais_option > 0) {
              feeText += " + " + data.frais_option + " Ar (frais de retrait)";
              currentFraisOption = data.frais_option;
            } else {
              currentFraisOption = 0;
            }
            if (data.frais_retrait && data.frais_retrait > 0) {
              currentFraisRetrait = data.frais_retrait;
            } else {
              currentFraisRetrait = 0;
            }
            setFee(feeText, false);
            currentFrais = data.frais;
          } else {
            setFee("Erreur : réponse inattendue du serveur", true);
            currentFrais = null;
            currentFraisOption = 0;
            currentFraisRetrait = 0;
          }
        })
        .catch(function (err) {
          console.error("Erreur calcul frais:", err);
          setFee("Erreur: " + err.message, true);
          currentFrais = null;
          currentFraisOption = 0;
          currentFraisRetrait = 0;
        });
    }

    form.addEventListener("submit", function (e) {
      e.preventDefault();
      var montant = montantInput.value;
      var beneficiaire = getBeneficiaire();

      if (!montant || currentFrais === null) {
        window.alert(
          "Veuillez remplir tous les champs et attendre le calcul des frais.",
        );
        return;
      }
      if (beneficiaireInput && !beneficiaire) {
        window.alert(
          "Veuillez remplir tous les champs et attendre le calcul des frais.",
        );
        return;
      }

      var body =
        "montant=" +
        encodeURIComponent(montant) +
        "&frais_applique=" +
        encodeURIComponent(currentFrais) +
        "&frais_option_applique=" +
        encodeURIComponent(currentFraisOption) +
        "&" +
        csrfName +
        "=" +
        csrfHash;

      if (beneficiaireInput && beneficiaire) {
        body += "&beneficiaire=" + encodeURIComponent(beneficiaire);
      }

      if (submitBtn) submitBtn.setAttribute("disabled", "disabled");

      fetch(submitUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: body,
      })
        .then(function (r) {
          return r.text();
        })
        .then(function (text) {
          var data;
          try {
            data = JSON.parse(text);
          } catch (e) {
            throw new Error("Réponse invalide du serveur");
          }
          return data;
        })
        .then(function (data) {
          if (data.success) {
            window.alert(
              "Opération effectuée avec succès ! Nouveau solde : " +
                data.nouveau_solde +
                " Ar",
            );
            form.reset();
            setFee("Frais : —", false);
            currentFrais = null;
          } else {
            window.alert("Erreur : " + (data.error || "Erreur inconnue"));
          }
        })
        .catch(function (err) {
          window.alert(
            "Erreur lors de l'enregistrement de l'opération : " + err.message,
          );
        })
        .finally(function () {
          if (submitBtn) submitBtn.removeAttribute("disabled");
        });
    });
  }

  /* ---------------------------------------------------------------------
     Toggle transfert simple / multiple
     --------------------------------------------------------------------- */
  function initToggleMultiTransfert() {
    var toggleBtn = document.getElementById("toggle-mode-transfert");
    var simpleSection = document.getElementById("transfert-simple-section");
    var multiSection = document.getElementById("multi-transfert-section");
    if (!toggleBtn || !simpleSection || !multiSection) return;

    var isMultiple = false;
    var multiInitialized = false;

    toggleBtn.addEventListener("click", function () {
      isMultiple = !isMultiple;

      if (isMultiple) {
        simpleSection.style.display = "none";
        multiSection.style.display = "block";
        toggleBtn.innerHTML = '<svg class="icon icon-sm" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9" /></svg> Retour au transfert simple';
        toggleBtn.classList.remove("btn-secondary");
        toggleBtn.classList.add("btn-warning");
        if (!multiInitialized) {
          initMultiTransfert();
          multiInitialized = true;
        }
      } else {
        simpleSection.style.display = "block";
        multiSection.style.display = "none";
        toggleBtn.innerHTML = '<svg class="icon icon-sm" viewBox="0 0 24 24"><path d="M17 1l4 4-4 4" /><path d="M3 11V9a4 4 0 014-4h14" /><path d="M7 23l-4-4 4-4" /><path d="M21 13v2a4 4 0 01-4 4H3" /></svg> Faire un transfert multiple';
        toggleBtn.classList.remove("btn-warning");
        toggleBtn.classList.add("btn-secondary");
      }
    });
  }

  /* ---------------------------------------------------------------------
     Transfert multiple : duplication de formulaire et envoi groupé
     --------------------------------------------------------------------- */
  function initMultiTransfert() {
    var container = document.getElementById("transfert-container");
    var addBtn = document.getElementById("add-transfert");
    var submitAllBtn = document.getElementById("submit-all-transferts");
    var errorsDiv = document.getElementById("transfert-errors");
    var montantTotalInput = document.getElementById("montant-total");
    var fraisTotalDisplay = document.getElementById("frais-total-display");
    var ajouterFraisRetraitCheckbox = document.getElementById("ajouter-frais-retrait-multiple");
    if (!container || !addBtn || !submitAllBtn || !montantTotalInput || !fraisTotalDisplay) return;

    var clientsData = container.getAttribute("data-clients");
    var clients = [];
    if (clientsData) {
      try { clients = JSON.parse(clientsData); } catch (e) { clients = []; }
    }

    var transfertCount = 1;
    var fraisUrl = container.getAttribute("data-frais-url") || "";
    var submitUrl = container.getAttribute("data-submit-url") || "";
    var csrfName = container.getAttribute("data-csrf-name") || "";
    var csrfHash = container.getAttribute("data-csrf-hash") || "";
    var currentFraisTotal = 0;
    var currentFraisOptionTotal = 0;
    var currentFraisRetraitTotal = 0;

    var initialBeneficiaireInput = document.getElementById("beneficiaire_0");
    if (initialBeneficiaireInput) {
      console.log("[DEBUG] Validation 034 activée sur beneficiaire_0");
    } else {
      console.warn("[DEBUG] beneficiaire_0 introuvable dans le transfert multiple");
    }

    function updateMontantParts() {
      var cards = container.querySelectorAll(".transfert-card");
      var montantTotal = parseFloat(montantTotalInput.value) || 0;
      if (montantTotal <= 0) {
        cards.forEach(function (card) {
          var partInput = card.querySelector(".montant-part");
          if (partInput) partInput.value = "0";
        });
        return;
      }
      var part = montantTotal / cards.length;
      cards.forEach(function (card) {
        var partInput = card.querySelector(".montant-part");
        if (partInput) partInput.value = Math.floor(part).toLocaleString("fr-FR");
      });
    }

    function updateFraisTotal() {
      var montantTotal = montantTotalInput.value;
      if (!montantTotal) {
        fraisTotalDisplay.innerHTML = '<svg class="icon icon-sm" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12" y2="13" /><line x1="12" y1="16" x2="12.01" y2="16" /></svg> Frais : —';
        fraisTotalDisplay.classList.remove("is-error");
        currentFraisTotal = 0;
        return;
      }

      var premierBeneficiaire = "";
      var cardsTmp = container.querySelectorAll(".transfert-card");
      for (var i = 0; i < cardsTmp.length; i++) {
        var idxTmp = cardsTmp[i].getAttribute("data-index");
        var inputTmp = cardsTmp[i].querySelector("#beneficiaire_" + idxTmp);
        if (inputTmp && inputTmp.value.trim()) {
          premierBeneficiaire = inputTmp.value.trim();
          break;
        }
      }

      var body =
        "montant=" +
        encodeURIComponent(montantTotal) +
        (premierBeneficiaire ? "&beneficiaire=" + encodeURIComponent(premierBeneficiaire) : "") +
        "&ajouter_frais_retrait=" +
        (ajouterFraisRetraitCheckbox && ajouterFraisRetraitCheckbox.checked ? "1" : "0") +
        "&" +
        csrfName +
        "=" +
        csrfHash;

      fetch(fraisUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: body,
      })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          if (data.error) {
            fraisTotalDisplay.innerHTML = '<svg class="icon icon-sm" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12" y2="13" /><line x1="12" y1="16" x2="12.01" y2="16" /></svg> ' + data.error;
            fraisTotalDisplay.classList.add("is-error");
            currentFraisTotal = 0;
            currentFraisOptionTotal = 0;
            currentFraisRetraitTotal = 0;
          } else {
            var feeText = 'Frais : ' + data.frais + ' Ar';
            if (data.commission_valeur && data.commission_valeur > 0) {
              feeText += ' + ' + data.commission_valeur + ' Ar (commission)';
            }
            if (data.frais_option && data.frais_option > 0) {
              feeText += ' + ' + data.frais_option + ' Ar (frais de retrait)';
              currentFraisOptionTotal = data.frais_option;
            } else {
              currentFraisOptionTotal = 0;
            }
            if (data.frais_retrait && data.frais_retrait > 0) {
              currentFraisRetraitTotal = data.frais_retrait;
            } else {
              currentFraisRetraitTotal = 0;
            }
            fraisTotalDisplay.innerHTML = '<svg class="icon icon-sm" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12" y2="13" /><line x1="12" y1="16" x2="12.01" y2="16" /></svg> ' + feeText;
            fraisTotalDisplay.classList.remove("is-error");
            currentFraisTotal = data.frais;
          }
        })
        .catch(function () {
          fraisTotalDisplay.innerHTML = '<svg class="icon icon-sm" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12" y2="13" /><line x1="12" y1="16" x2="12.01" y2="16" /></svg> Erreur lors du calcul des frais';
          fraisTotalDisplay.classList.add("is-error");
          currentFraisTotal = 0;
          currentFraisOptionTotal = 0;
          currentFraisRetraitTotal = 0;
        });
    }

    if (ajouterFraisRetraitCheckbox) {
      ajouterFraisRetraitCheckbox.addEventListener("change", function () {
        if (montantTotalInput.value) {
          updateFraisTotal();
        }
      });
    }

    function buildCard(index) {
      var card = document.createElement("div");
      card.className = "card transfert-card";
      card.setAttribute("data-index", index);
      card.innerHTML =
        '<div class="card-body">' +
          '<button type="button" class="btn btn-danger btn-sm remove-transfert" style="float: right;" title="Supprimer ce transfert">' +
            '<svg class="icon icon-sm" viewBox="0 0 24 24">' +
              '<polyline points="3 6 5 6 21 6" />' +
              '<path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />' +
              '<path d="M10 11v6" />' +
              '<path d="M14 11v6" />' +
            '</svg>' +
          '</button>' +
          '<div class="field">' +
            '<label for="beneficiaire_' + index + '" class="field-label">Beneficiaire (Numero Telma 034)</label>' +
            '<input type="text" class="control beneficiaire-input" id="beneficiaire_' + index + '" placeholder="034 00 000 00" required>' +
          '</div>' +
          '<div class="field">' +
            '<label class="field-label">Montant par beneficiaire</label>' +
            '<div class="control-amount">' +
              '<input type="text" class="control montant-part" id="montant-part_' + index + '" readonly>' +
              '<span class="suffix">Ar</span>' +
            '</div>' +
          '</div>' +
        '</div>';

        var input = card.querySelector("#beneficiaire_" + index);
        if (input) {
          console.log("[DEBUG] Validation 034 activée sur beneficiaire_" + index);
        }

      return card;
    }

    function updateRemoveButtons() {
      var cards = container.querySelectorAll(".transfert-card");
      var removeButtons = container.querySelectorAll(".remove-transfert");
      if (cards.length <= 1) {
        removeButtons.forEach(function (btn) { btn.setAttribute("disabled", "disabled"); });
      } else {
        removeButtons.forEach(function (btn) { btn.removeAttribute("disabled"); });
      }
    }

    montantTotalInput.addEventListener("input", function () {
      updateMontantParts();
      updateFraisTotal();
    });

    addBtn.addEventListener("click", function () {
      var card = buildCard(transfertCount);
      container.appendChild(card);
      transfertCount++;
      updateMontantParts();
      updateRemoveButtons();
    });

    container.addEventListener("click", function (e) {
      var btn = e.target.closest(".remove-transfert");
      if (!btn) return;
      if (btn.hasAttribute("disabled")) return;
      var card = btn.closest(".transfert-card");
      if (!card) return;
      card.remove();
      updateMontantParts();
      updateRemoveButtons();
    });

    updateRemoveButtons();
    updateMontantParts();

    submitAllBtn.addEventListener("click", function () {
      var cards = container.querySelectorAll(".transfert-card");
      var promises = [];
      var beneficiaires = [];
      var hasError = false;

      cards.forEach(function (card) {
        var idx = card.getAttribute("data-index");
        var beneficiaireInput = card.querySelector("#beneficiaire_" + idx);
        var beneficiaire = beneficiaireInput ? beneficiaireInput.value.trim() : "";

        if (!beneficiaire) {
          errorsDiv.innerHTML = '<div class="alert alert-danger"><span>Transfert #' + (parseInt(idx) + 1) + ' : beneficiaire manquant.</span></div>';
          hasError = true;
          return;
        }

        var prefixe = beneficiaire.substring(0, 3);
        if (prefixe !== "034") {
          errorsDiv.innerHTML = '<div class="alert alert-danger"><span>Transfert #' + (parseInt(idx) + 1) + ' : seul l\'operateur Telma (034) est autorise pour le transfert multiple.</span></div>';
          hasError = true;
          return;
        }

        beneficiaires.push(beneficiaire);
      });

      if (hasError || beneficiaires.length === 0) {
        return;
      }

      var montantTotal = montantTotalInput.value;
      if (!montantTotal || currentFraisTotal === 0) {
        errorsDiv.innerHTML = '<div class="alert alert-danger"><span>Veuillez entrer un montant total et attendre le calcul des frais.</span></div>';
        return;
      }

      var body =
        "beneficiaires[]=" + beneficiaires.map(encodeURIComponent).join("&beneficiaires[]=") +
        "&montant_total=" + encodeURIComponent(montantTotal) +
        "&frais_applique=" + encodeURIComponent(currentFraisTotal) +
        "&frais_option_applique=" + encodeURIComponent(currentFraisOptionTotal) +
        "&" + csrfName + "=" + encodeURIComponent(csrfHash);

      submitAllBtn.setAttribute("disabled", "disabled");

      fetch(submitUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: body,
      })
        .then(function (r) { return r.text(); })
        .then(function (text) {
          var data;
          try { data = JSON.parse(text); } catch (e) { throw new Error("Réponse invalide"); }
          return data;
        })
        .then(function (data) {
          if (data.success) {
            window.alert(data.count + " transfert(s) effectué(s) avec succès ! Nouveau solde : " + data.nouveau_solde + " Ar");
            container.innerHTML = "";
            transfertCount = 1;
            container.appendChild(buildCard(0));
            transfertCount = 1;
            montantTotalInput.value = "";
            fraisTotalDisplay.innerHTML = '<svg class="icon icon-sm" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12" y2="13" /><line x1="12" y1="16" x2="12.01" y2="16" /></svg> Frais : —';
            fraisTotalDisplay.classList.remove("is-error");
            currentFraisTotal = 0;
            currentFraisOptionTotal = 0;
            currentFraisRetraitTotal = 0;
            updateMontantParts();
            errorsDiv.innerHTML = "";
          } else {
            errorsDiv.innerHTML = '<div class="alert alert-danger"><span>' + (data.error || "Erreur inconnue") + '</span></div>';
          }
        })
        .catch(function (err) {
          errorsDiv.innerHTML = '<div class="alert alert-danger"><span>Erreur lors de l\'enregistrement : ' + err.message + '</span></div>';
        })
        .finally(function () {
          submitAllBtn.removeAttribute("disabled");
        });
    });
  }
})();