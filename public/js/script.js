/* =========================================================================
   MOBILE MONEY — script.js
   Comportements partages : sidebar admin, alertes, confirmation de
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
    var beneficiaireSelect = form.querySelector("#beneficiaire");
    var fraisUrl = form.getAttribute("data-frais-url");
    var submitUrl = form.getAttribute("data-submit-url");
    var csrfName = form.getAttribute("data-csrf-name");
    var csrfHash = form.getAttribute("data-csrf-hash");
    var currentFrais = null;

    if (!montantInput || !feeDisplay || !fraisUrl) return;

    function setFee(text, isError) {
      feeDisplay.textContent = text;
      feeDisplay.classList.toggle("is-error", !!isError);
    }

    montantInput.addEventListener("input", function () {
      var montant = this.value;
      if (!montant) {
        setFee("Frais : —", false);
        currentFrais = null;
        return;
      }

      var body =
        "montant=" +
        encodeURIComponent(montant) +
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
          return r.json();
        })
        .then(function (data) {
          if (data.error) {
            setFee(data.error, true);
            currentFrais = null;
          } else {
            setFee("Frais : " + data.frais + " Ar", false);
            currentFrais = data.frais;
          }
        })
        .catch(function () {
          setFee("Erreur lors du calcul des frais", true);
          currentFrais = null;
        });
    });

    form.addEventListener("submit", function (e) {
      e.preventDefault();
      var montant = montantInput.value;
      var beneficiaire = beneficiaireSelect ? beneficiaireSelect.value : null;

      if (
        !montant ||
        currentFrais === null ||
        (beneficiaireSelect && !beneficiaire)
      ) {
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
        (beneficiaireSelect
          ? "&beneficiaire=" + encodeURIComponent(beneficiaire)
          : "") +
        "&" +
        csrfName +
        "=" +
        csrfHash;

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
})();
