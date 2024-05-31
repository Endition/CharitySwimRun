<div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Meldungen</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-lg">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Nachricht</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									{foreach from=$nachrichten key=schluessel item=value}
										<tr>
											<td class="col-3">
											<div class="d-flex align-items-center">
												<div class="avatar avatar-md">
													<i style="color:white;"class="fa fa-message fa-fw"></i>
												</div>
												<p class="font-bold ms-3 mb-0">
												{$value['tn']->getVorname()} {$value['tn']->getName()}
												</p>
											</div>
										</td>
										<td class="col-auto">
											<p class=" mb-0">{$value['tn']->getAltersklasse()->getAltersklasseKurz()}: {$value['text']}</p>
										</td>
										</tr>	               
											{/foreach}  
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>