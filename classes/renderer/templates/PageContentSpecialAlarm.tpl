<div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Alarme</h4>
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
									{foreach from=$teilnehmerWrongStreckeList item=teilnehmer}
										<tr>
										<td class="col-3">
										<div class="d-flex align-items-center">
											<div class="avatar avatar-md">
												<i style="color:red;"class="fa fa-bell fa-fw"></i>
											</div>
											<p class="font-bold ms-3 mb-0">
												<a href="index.php?doc=teilnehmer&id={$teilnehmer->getId()}&action=search">{$teilnehmer->getGesamtname()}</a>
											</p>
										</div>
									</td>
									<td class="col-auto">
										<p class=" mb-0">Keine Strecke zugeordnet</p>
									</td>
									</tr>
									{/foreach}  
									{foreach from=$teilnehmerWrongAltersklasseList item=teilnehmer}
										<tr>
										<td class="col-3">
										<div class="d-flex align-items-center">
											<div class="avatar avatar-md">
												<i style="color:red;"class="fa fa-bell fa-fw"></i>
											</div>
											<p class="font-bold ms-3 mb-0">
												<a href="index.php?doc=teilnehmer&id={$teilnehmer->getId()}&action=search">{$teilnehmer->getGesamtname()}</a>
											</p>
										</div>
									</td>
									<td class="col-auto">
										<p class=" mb-0">Keine Altersklasse zugeordnet</p>
									</td>
									</tr>
									{/foreach}  
									{foreach from=$teilnehmerWrongStartzeit1List item=teilnehmer}
										<tr>
										<td class="col-3">
										<div class="d-flex align-items-center">
											<div class="avatar avatar-md">
												<i style="color:red;"class="fa fa-bell fa-fw"></i>
											</div>
											<p class="font-bold ms-3 mb-0">
												<a href="index.php?doc=teilnehmer&id={$teilnehmer->getId()}&action=search">{$teilnehmer->getGesamtname()}</a>
											</p>
										</div>
									</td>
									<td class="col-auto">
										<p class=" mb-0">Startzeit in der Zukunft</p>
									</td>
									</tr>
									{/foreach}  
									{foreach from=$teilnehmerWrongStartzeit2List item=teilnehmer}
										<tr>
										<td class="col-3">
										<div class="d-flex align-items-center">
											<div class="avatar avatar-md">
												<i style="color:red;"class="fa fa-bell fa-fw"></i>
											</div>
											<p class="font-bold ms-3 mb-0">
												<a href="index.php?doc=teilnehmer&id={$teilnehmer->getId()}&action=search">{$teilnehmer->getGesamtname()}</a>
											</p>
										</div>
									</td>
									<td class="col-auto">
										<p class=" mb-0">Falsche Startzeit (Buchungen vor Start) zugeordnet</p>
									</td>
									</tr>
									{/foreach}  	
									{foreach from=$teilnehmerWrongTransponderList item=impuls}
										<tr>
										<td class="col-3">
										<div class="d-flex align-items-center">
											<div class="avatar avatar-md">
												<i style="color:red;"class="fa fa-bell fa-fw"></i>
											</div>
											<p class="font-bold ms-3 mb-0">
												{$impuls->getTransponderId()}, Leser: {$impuls->getLeser()}, Zeit: {$impuls->getTimestamp()}
											</p>
										</div>
									</td>
									<td class="col-auto">
										<p class=" mb-0">kein Teilnehmer zugeordnet</p>
									</td>
									</tr>
									{/foreach}  								

  
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


	

	                