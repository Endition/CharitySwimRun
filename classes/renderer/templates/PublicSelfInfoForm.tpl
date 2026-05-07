<style>
/* Defines the styling for the self-service kiosk terminal. */
.kiosk-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
}

/* Styles the large numpad input field. */
.kiosk-input {
    font-size: 4rem !important;
    height: 100px !important;
    text-align: center;
    font-weight: 700;
    letter-spacing: 5px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
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

/* Styles individual result cards. */
.result-card {
    border-radius: 12px;
    padding: 20px 25px;
    margin-bottom: 15px;
    border: 1px solid rgba(0,0,0,0.1);
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    animation: slideDownCard 0.4s ease-out forwards;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 1.3rem;
}

/* Dark mode adjustments for result cards. */
[data-bs-theme="dark"] .result-card {
    border: 1px solid rgba(255,255,255,0.1);
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

/* Highlights the error cards. */
.error-card {
    border-left: 5px solid #EF4444;
}

/* Highlights the success cards. */
.success-card {
    border-left: 5px solid #10B981;
}

/* Emphasizes specific data points. */
.data-badge {
    background: rgba(56, 189, 248, 0.1);
    color: #38BDF8;
    padding: 5px 15px;
    border-radius: 20px;
    font-weight: bold;
    margin: 0 5px;
    display: inline-block;
}

.time-stamp {
    font-size: 1.1rem;
    color: var(--bs-gray-500);
}
</style>

<div class="kiosk-container">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body text-center p-5">
            <h2 class="mb-3">Selbstauskunft</h2>
            <p class="text-muted mb-4" style="font-size: 1.2rem;">
                Tippe deine Startnummer über den Ziffernblock ein und drücke <strong>Enter</strong>.<br>
                <small>(Aus Datenschutzgründen wird dein Name nicht angezeigt)</small>
            </p>
            
            <div class="form-group mb-0">
                <input type="number" class="form-control kiosk-input" name="startnummer" id="startnummer" placeholder="StNr" required autofocus autocomplete="off">
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
                        '<div class="result-card error-card new-kiosk-entry flex-column align-items-start flex-md-row align-items-md-center">' +
                            '<div class="mb-2 mb-md-0 time-stamp">' + uhrzeit + ' Uhr</div>' +
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
                        '<div class="result-card success-card new-kiosk-entry flex-column align-items-start flex-xl-row align-items-xl-center">' +
                            '<div class="mb-2 mb-xl-0 time-stamp">' + uhrzeit + ' Uhr</div>' +
                            '<div class="d-flex flex-wrap align-items-center gap-2">' +
                                '<span>StNr <span class="data-badge">' + data.startnummer + '</span></span>' +
                                '<span>Meter <span class="data-badge">' + data.meter + 'm</span></span>' +
                                '<span>Wertung <span class="data-badge">' + data.wertung + '</span></span>' +
                                '<span>Nächste <span class="data-badge">' + data.naechsteWertung + '</span></span>' +
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
