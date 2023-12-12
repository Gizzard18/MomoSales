<div wire:ignore.self id="modalDelivery" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-third">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Cliente | Envío | Facturación</h4>
            </div>
            <div class="modal-body">
                <form action="" autocomplete="new">
                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <label>First Name*</label>
                            <input wire:model.defer="entrega.first_name" id='inputDeliveryName' type="text"
                                class="form-control form-control-lg" placeholder="first name" autocomplete="none">
                            @error('entrega.first_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label>Last Name</label>
                            <input wire:model.defer="entrega.last_name" type="text"
                                class="form-control form-control-lg" placeholder="last name">
                            @error('entrega.last_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label>Company</label>
                            <input wire:model.defer="entrega.company" type="text" class="form-control form-control-lg"
                                placeholder="company">
                            @error('entrega.company') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <label>Address 1</label>
                            <input wire:model.defer="entrega.primary_address" type="text" class="form-control form-control-lg"
                                placeholder="address1">
                            @error('entrega.primary_address') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label>Address 2</label>
                            <input wire:model.defer="entrega.secondary_address" type="text" class="form-control form-control-lg"
                                placeholder="address2">
                            @error('entrega.secondary_address') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <label>City</label>
                            <input wire:model.defer="entrega.city" type="text" class="form-control form-control-lg"
                                placeholder="city">
                            @error('entrega.city') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label>State</label>
                            <select wire:model.defer="entrega.state" class="form-control form-control-lg">
                                <option value="">Select State</option>
                                <option value="JC">Jalisco</option>
                                <option value="CX">Ciudad de México</option>
                                <option value="AG">Aguascalientes</option>
                                <option value="BC">Baja California</option>
                                <option value="BS">Baja California Sur</option>
                                <option value="CC">Campeche</option>
                                <option value="CS">Chiapas</option>
                                <option value="CH">Chihuahua</option>
                                <option value="CL">Coahuila</option>
                                <option value="CM">Colima</option>
                                <option value="DG">Durango</option>
                                <option value="GT">Guanajuato</option>
                                <option value="GR">Guerrero</option>
                                <option value="HG">Hidalgo</option>
                                <option value="EM">Estado de México</option>
                                <option value="MN">Michoacán</option>
                                <option value="MS">Morelos</option>
                                <option value="NT">Nayarit</option>
                                <option value="NL">Nuevo León</option>
                                <option value="OC">Oaxaca</option>
                                <option value="PL">Puebla</option>
                                <option value="QO">Querétaro</option>
                                <option value="QR">Quintana Roo</option>
                                <option value="SP">Sinaloa</option>
                                <option value="SL">San Luis Potosí</option>
                                <option value="SR">Sonora</option>
                                <option value="TC">Tabasco</option>
                                <option value="TS">Tamaulipas</option>
                                <option value="TL">Tlaxcala</option>
                                <option value="VZ">Veracruz</option>
                                <option value="YN">Yucatán</option>
                                <option value="ZS">Zacatecas</option>
                            </select>
                            @error('entrega.state') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label>Post Code</label>
                            <input wire:model.defer="entrega.postcode" type="text" class="form-control form-control-lg"
                                placeholder="postcode">
                            @error('entrega.postcode') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <label>Email</label>
                            <input wire:model.defer="entrega.email" type="text" class="form-control form-control-lg"
                                placeholder="email">
                            @error('entrega.email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label>Phone</label>
                            <input wire:model.defer="entrega.phone" type="text" class="form-control form-control-lg"
                                placeholder="phone">
                            @error('entrega.phone') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label>Country</label>
                            <input wire:model.defer="entrega.country" type="text" class="form-control form-control-lg"
                                placeholder="country">
                            @error('entrega.country') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                    </div>
                    <div class="row mt-4 d-flex align-items-center">
                        <div class="col-sm-12 col-md-4">
                            <select wire:model.defer="entrega.type" class="form-control form-control-lg">
                                <option value="billing">Billing</option>
                                <option value="shipping">Shipping</option>
                            </select>
                            @error('entrega.type') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-sm-12 col-md-8 ">
                            @if(!isset($entrega['id']))
                            <button class="btn btn-sm btn-info float-right save"
                                wire:click.prevent="saveDelivery" style="background-color: #B59377;border-color:#B59377;;">Save</button>
                            @else
                            <button class="btn btn-sm btn-dark float-right"
                                wire:click.prevent="cancelEditDelivery">Cancel</button>
                            <button class="btn btn-sm btn-info float-right save mr-4"
                                wire:click.prevent="updateDelivery" style="background-color:#B59377;border-color:#B59377;">Update</button>
                            @endif
                        </div>
                    </div>


                </form>

                <div class="row mt-4">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-responsive-md table-hover  text-center">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="text-left">First Name</th>
                                        <th>Address</th>
                                        <th>Type</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($customerSelected !=null)
                                    @foreach ($customerSelected->deliveries as $dely)
                                    <tr>
                                        <td class="text-left">{{$dely->first_name}}</td>
                                        <td> {{$dely->primary_address}}</td>
                                        <td> {{ucfirst($dely->type)}}</td>
                                        <td>
                                            <button wire:click.prevent="editDelivery({{ $dely->id }})"
                                                class="btn  tp-btn btn-dark btn-xxs"><i class="las la-edit la-2x"></i>
                                            </button>
                                            <button wire:click.prevent="removeDelivery({{ $dely->id }})"
                                                class="btn  tp-btn btn-dark btn-xxs"><i class="las la-trash la-2x"></i>
                                            </button>
                                        </td>

                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>