const stateColors = wa_golfs_calendar.state_colors;
const stateLabels = wa_golfs_calendar.state_labels;

document.addEventListener("DOMContentLoaded", function () {
  var calendarEl = document.getElementById("competitions-calendar");
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    locale: "fr", // Set the locale to French
    views: {
      multiMonthYear: {
        type: "multiMonth",
        buttonText: "Année",
      },
      multiMonthBi: {
        type: "multiMonth",
        duration: { months: 2 },
        buttonText: "Bimensuel",
      },
      month: {
        type: "dayGridMonth",
        buttonText: "Mois",
      },
      continuousMonth: {
        type: "dayGrid",
        duration: { weeks: 4 },
        buttonText: "Glissant",
      },
    },

    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "multiMonthYear,multiMonthBi,month,continuousMonth",
    },

    events: function (fetchInfo, successCallback, failureCallback) {
      jQuery.ajax({
        url: "/wp-admin/admin-ajax.php",
        method: "POST",
        data: {
          action: "get_competitions_events",
        },
        success: function (response) {
          successCallback(response);
        },
        error: function () {
          failureCallback();
        },
      });
    },

    eventClick: function (info) {
      info.jsEvent.preventDefault(); // don't let the browser navigate

      // Remove any existing popups
      var existingPopup = document.getElementById("calendar-popup");
      if (existingPopup) {
        document.body.removeChild(existingPopup);
      }

      var event = info.event;
      var state = event.extendedProps.state;
      var content = `
        ${
          event.extendedProps.thumbnail
            ? `
        <div style="text-align:center;height: 150px;">
          <img src="${event.extendedProps.thumbnail}" alt="Event Thumbnail" style="width: 100%; height: 150px; border-radius: 4px 4px 0 0; object-fit: cover;">
        </div>`
            : ""
        }
        <div style="padding:10px;">
          <h3>${event.title}</h3>
          <p><span class="new" style="color: ${
            stateColors[state]?.textColor
          };background-color: ${stateColors[state]?.backgroundColor}">${
        stateLabels[state]?.label
      }</span></p>
          
          <a href="${
            event.extendedProps.external_link
          }" target="_blank" class="no-underline"><span class="new"><span class="dashicons-before dashicons-admin-links"></span> Lien FFGOLF</span></a>
          <a href="${
            event.extendedProps.permalink
          }" class="no-underline"><span class="label"><span class="dashicons-before dashicons-visibility"> Voir</span></a>
          <a href="${
            event.extendedProps.edit_link
          }" class="no-underline"><span class="label"><span class="dashicons-before dashicons-edit"></span> Modifier</span></a>
        </div>
        `;

      const popup = document.createElement("div");
      popup.innerHTML = content;
      popup.id = "calendar-popup";
      popup.style.position = "absolute";
      popup.style.backgroundColor = "#fff";
      popup.style.border = "1px solid #ccc";
      popup.style.zIndex = 1000;
      popup.style.width = "150px";
      popup.style.height = "auto";
      popup.style.borderRadius = "4px";
      popup.style.boxShadow = "0 4px 8px rgba(0, 0, 0, 0.1)";
      document.body.appendChild(popup);

      var rect = info.jsEvent.target.getBoundingClientRect();
      popup.style.left = rect.left + "px";
      popup.style.top = rect.bottom + "px";

      var closeButton = document.createElement("button");
      closeButton.innerHTML =
        "<span class='dashicons-before dashicons-dismiss'></span>";
      closeButton.style.color = "white";
      closeButton.style.position = "absolute";
      closeButton.style.top = "5px";
      closeButton.style.right = "5px";
      closeButton.style.display = "block";
      closeButton.style.marginTop = "10px";
      closeButton.style.webkitAppearance = "none";
      closeButton.style.mozAppearance = "none";
      closeButton.style.msAppearance = "none";
      closeButton.style.oAppearance = "none";
      closeButton.style.appearance = "none";
      closeButton.style.border = "none";
      closeButton.style.background = "none";
      closeButton.style.padding = "0";
      closeButton.style.margin = "0";
      closeButton.style.cursor = "pointer";
      //   closeButton.style.float = "right";
      closeButton.addEventListener("click", function () {
        document.body.removeChild(popup);
      });
      popup.prepend(closeButton);

      document.body.addEventListener("click", function (event) {
        var existingPopup = document.getElementById("calendar-popup");

        if (existingPopup) {
          // If we have a popup, we check the position of target in DOM
          if (event.target.className !== "fc-event-title") {
            // If we do not click on event title, we have to do something !
            if (
              !(event.target.closest("div")?.id.includes("calendar-popup") ||
              event.target.parentElement
                ? event.target.parentElement
                    .closest("div")
                    ?.id.includes("calendar-popup")
                : false || event.target.parentElement.parentElement
                ? event.target.parentElement.parentElement
                    .closest("div")
                    ?.id.includes("calendar-popup")
                : false)
            ) {
              // We do NOT have clicked inside calendar-popup directly or on a second or third level, then remove
              document.body.removeChild(existingPopup);
            }
          }
        }
      });
    },

    eventDidMount: function (info) {
      var event = info.event;
      var state = event.extendedProps.state;
      if (state && stateColors[state]) {
        // Change element color & bg
        info.el.style.color = stateColors[state].textColor;
        info.el.style.backgroundColor = stateColors[state].backgroundColor;

        // Change color of dot marker
        var dotEl = info.el.getElementsByClassName("fc-daygrid-event-dot")[0];
        if (dotEl) {
          dotEl.style.borderColor = stateColors[state].textColor;
        }
      }
    },

    dayCellDidMount: function (info) {
      if (info.date.getTime() === new Date().setHours(0, 0, 0, 0)) {
        info.el.style.backgroundColor = "rgb(228, 235, 242)"; // Change the background color of the current day
      }
    },
  });
  calendar.render();
});
