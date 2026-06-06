<style>
/* Styles the large numpad input field. */
.kiosk-input {
    font-size: 4rem !important;
    height: 100px !important;
    letter-spacing: 5px;
    border: 2px solid #38BDF8;
}

/* Ensures smooth focus transition. */
.kiosk-input:focus {
    border-color: #818CF8;
    box-shadow: 0 10px 30px rgba(129, 140, 248, 0.2);
    outline: none;
}

/* Animates incoming result cards. */
@keyframes slideDownCard {
    from { opacity: 0; transform: translateY(-20px) scale(0.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

/* Creates a temporary glow for newly added results. */
@keyframes kioskGlow {
    0% { box-shadow: 0 0 20px rgba(16, 185, 129, 0.4); border-color: rgba(16, 185, 129, 0.8); }
    100% { box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-color: rgba(255,255,255,0.1); }
}

/* Dark mode adjustments for result cards. */
[data-bs-theme="dark"] .result-card {
    background: rgba(30, 41, 59, 0.5);
}

/* Light mode adjustments for result cards. */
[data-bs-theme="light"] .result-card {
    background: #ffffff;
}

/* Applies the glow effect to the newest entry. */
.new-kiosk-entry {
    animation: slideDownCard 0.4s ease-out forwards, kioskGlow 3s ease-out forwards;
}
.result-card {
    animation: slideDownCard 0.4s ease-out forwards;
}
</style>

<div class="container p-4 mx-auto" style="max-width: 1400px;">
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body text-center p-5">
            <h2 class="mb-3 fw-bold">Selbstauskunft</h2>
            <p class="text-muted mb-4 fs-5">
                Tippe deine Startnummer über den Ziffernblock ein und drücke <strong>Enter</strong>.<br>
                <small class="fs-6">(Aus Datenschutzgründen wird dein Name nicht angezeigt)</small>
            </p>
            
            <div class="form-group mb-0">
                <input type="number" class="form-control kiosk-input text-center fw-bold rounded-4 shadow-sm" name="startnummer" id="startnummer" placeholder="StNr" required autofocus autocomplete="off">
            </div>
        </div>
    </div>

    <div id="zieldiv">
        <!-- Results will be injected here -->
    </div>
</div>

<script>
    $(document).ready(function() {
        /* Focuses the input field automatically on load. */
        $('#startnummer').focus();
        
        /* Keeps focus on input if user clicks elsewhere, ensuring numpad always works. */
        $(document).click(function() {
            $('#startnummer').focus();
        });
    });

    var input = document.getElementById("startnummer");

    /**
     * Listens for the Enter key press.
     * Fetches participant data and displays it dynamically as a kiosk card.
     */
    input.addEventListener("keypress", function(event) {
        if (event.key === "Enter" && $('#startnummer').val().trim() !== "") { 
            var startNr = $("#startnummer").val().trim();
            
            $.ajax({ 
                url: "api/teilnehmer/startnummer/" + startNr,
                type: 'GET', 
                beforeSend: function(){
                    /* Removes the highlight class from older entries. */
                    $('#zieldiv').find('.result-card').removeClass('new-kiosk-entry');
                },
                error: function(jqXHR){
                    var today = new Date();
                    var uhrzeit = today.getHours() + ":" + (today.getMinutes() < 10 ? '0' : '') + today.getMinutes() + ":" + (today.getSeconds() < 10 ? '0' : '') + today.getSeconds();
                    
                    /* Clears the input field for the next user. */
                    $("#startnummer").val('');
                    
                    var errorMsg = jqXHR.responseJSON && jqXHR.responseJSON.message ? jqXHR.responseJSON.message : "Startnummer nicht gefunden.";
                    
                    /* Constructs and prepends the error card. */
                    var errorCard = $(
                        '<div class="result-card new-kiosk-entry d-flex flex-column align-items-start flex-md-row justify-content-between align-items-md-center rounded-3 p-4 mb-3 border border-danger border-5 border-top-0 border-end-0 border-bottom-0 shadow-sm fs-4">' +
                            '<div class="mb-2 mb-md-0 text-secondary fs-5">' + uhrzeit + ' Uhr</div>' +
                            '<div class="text-danger fw-bold">' + errorMsg + ' (StNr: ' + startNr + ')</div>' +
                        '</div>'
                    );
                    
                    $("#zieldiv").prepend(errorCard);
                    limitCards();
                },
                success: function(data){ 
                    var today = new Date();
                    var uhrzeit = today.getHours() + ":" + (today.getMinutes() < 10 ? '0' : '') + today.getMinutes() + ":" + (today.getSeconds() < 10 ? '0' : '') + today.getSeconds();
                    
                    /* Clears the input field for the next user. */
                    $("#startnummer").val('');
                    
                    /* Constructs and prepends the success card with detailed participant data. */
                    var successCard = $(
                        '<div class="result-card new-kiosk-entry d-flex flex-column align-items-start flex-lg-row justify-content-between align-items-lg-center rounded-3 p-4 mb-3 border border-success border-5 border-top-0 border-end-0 border-bottom-0 shadow-sm fs-4">' +
                            '<div class="mb-3 mb-lg-0 text-secondary fs-5">' + uhrzeit + ' Uhr</div>' +
                            '<div class="d-flex flex-wrap align-items-center gap-3">' +
                                '<span>StNr <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2 ms-1">' + data.startnummer + '</span></span>' +
                                '<span>Meter <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2 ms-1">' + data.meter + 'm</span></span>' +
                                '<span>Wertung <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2 ms-1">' + data.wertung + '</span></span>' +
                                '<span>Nächste <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2 ms-1">' + data.naechsteWertung + '</span></span>' +
                            '</div>' +
                        '</div>'
                    );

                    $("#zieldiv").prepend(successCard);
                    limitCards();
                    return false;
                }
            }); 
        }
    });

    /**
     * Limits history to 6 entries to keep the kiosk interface clean.
     */
    function limitCards() {
        if ($('#zieldiv .result-card').length > 6) {
            $('#zieldiv .result-card').last().fadeOut(300, function() { $(this).remove(); });
        }
    }
</script>
