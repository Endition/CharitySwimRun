<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="card-header">Sonderauswertung anlegen</h5>
            <div class="card-body">
                        <form role="form" name="specialEvaluationForm" id="specialEvaluationForm"
                              action="{$actionurl}" method="POST" class="form-horizontal">
                            <div class="form-group">
                                <label class="form-label">Bezeichnung der Sonderauswertung </label>
                                <div class="col-sm-8">
                                    <input class="form-control" value="{$specialEvaluation->getId()}" name="id" type="hidden" readonly="readonly" />
                                    <input class="form-control" value="{$specialEvaluation->getName()}" name="name" type="text" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Start</label>
                                 <div class="col-sm-8">
                                    <input class="form-control" type="datetime-local" value="{$specialEvaluation->getStart()->format("Y-m-d H:i:s")}" name="start" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ende</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="datetime-local" value="{$specialEvaluation->getEnd()->format("Y-m-d H:i:s")}" name="end" required />
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-label">Geschlecht </label>
                                <div class="col-sm-8">
                                    <select name="geschlecht" class="form-control">
                                    <option value="">bitte auswählen</option>
                                        {html_options options=$geschlechter selected=$specialEvaluation->getGeschlecht()} 
                                    </select>	
                                </div>
                            </div>	

                            <div class="form-group col-sm-6">
                                <label class="form-label">Strecke</label>
                                <div class="col-sm-8">
                                    <select 
                                        name="strecke"
                                        class="form-control">
                                    <option value="">bitte auswählen</option>
                                    {html_options options=$strecken selected=($specialEvaluation->getStrecke() ? $specialEvaluation->getStrecke()->getId() : null)}
                                    </select>									
                                </div>
                            </div>	

                            <div class="form-group col-sm-6">
                            <label class="form-label">Altersklasse</label>
                            <div class="col-sm-8">
                                <select 
                                    name="altersklasse"
                                    class="form-control">
                                    <option value="">bitte auswählen</option>
                                    {html_options options=$altersklassen selected=($specialEvaluation->getAltersklasse() ? $specialEvaluation->getAltersklasse()->getId() : null)}
                                </select>									
                            </div>
                        </div>	

                        <div class="form-group">
                        <label class="form-label">Leser</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="number" step="1" value="{$specialEvaluation->getLeser()}" name="leser" />
                        </div>
                    </div>


                            <div class="form-group">
                                <label class="form-label"></label>
                                <div class="col-sm-8">
                                    <button type="submit" name="sendSpecialEvaluationData"
                                            class="btn btn-primary">Speichern</button>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                </div>
                            </div>
                            
                        </form>
            </div>
        </div>
    </div>
</div>