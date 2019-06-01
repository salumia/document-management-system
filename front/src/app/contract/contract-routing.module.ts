import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { ListComponent } from './category/list/list.component';
import { EditComponent } from './category/edit/edit.component';

import { IndustryListComponent } from './industry/industry-list/industry-list.component';
import { IndustryEditComponent } from './industry/industry-edit/industry-edit.component';

import { ContractTypeListComponent } from './type/contract-type-list/contract-type-list.component';
import { ContractTypeEditComponent } from './type/contract-type-edit/contract-type-edit.component';

import { GlobalEditComponent } from './part/global-edit/global-edit.component';
import { GlobalListComponent } from './part/global-list/global-list.component';

import { PartEditComponent } from './part/part-edit/part-edit.component';
import { PartListComponent } from './part/part-list/part-list.component';

import { SetListComponent } from './set-list/set-list.component';
import { SetEditComponent } from './set-edit/set-edit.component';


import { CreateContractComponent } from './create-contract/create-contract.component';
import { ContractReviewComponent } from './contract-review/contract-review.component';
import { ContractPreviewComponent } from './contract-preview/contract-preview.component';
import { ContractListComponent } from './contract-list/contract-list.component';
import { AdminContractListComponent } from './admin-contract-list/admin-contract-list.component';
import { AccessContractComponent } from './access-contract/access-contract.component';
import { ContractSettingComponent } from './contract-setting/contract-setting.component';

const routes: Routes = [

  {path: 'categories', component: ListComponent},
  {path: 'category/create', component: EditComponent},
  {path: 'category/edit/:id', component: EditComponent}, 

  {path: 'industries', component: IndustryListComponent},
  {path: 'industry/create', component: IndustryEditComponent},
  {path: 'industry/edit/:id', component: IndustryEditComponent},
  
  {path: 'global-parts', component: GlobalListComponent},
  {path: 'global-part/create', component: GlobalEditComponent},
  {path: 'global-part/edit/:id', component: GlobalEditComponent},
  
  {path: 'parts', component: PartListComponent},
  {path: 'part/create', component: PartEditComponent},
  {path: 'part/edit/:id', component: PartEditComponent},
  
  {path: 'contract/types', component: ContractTypeListComponent},
  {path: 'contract-type/create', component: ContractTypeEditComponent},
  {path: 'contract-type/edit/:id', component: ContractTypeEditComponent},  
  
  {path: 'sets', component: SetListComponent},
  {path: 'set/create', component: SetEditComponent},
  {path: 'set/edit/:id', component: SetEditComponent},
  
  {path: 'contract/create', component: CreateContractComponent},
  {path: 'contract/edit/:id', component: CreateContractComponent},
  {path: 'contract/review/:id', component: ContractReviewComponent},
  {path: 'contract/preview/:id', component: ContractPreviewComponent},
  {path: 'contracts', component: ContractListComponent},
  {path: 'contract-list', component: AdminContractListComponent},
  {path: 'access-contract/:token', component: AccessContractComponent},
  {path: 'contract-settings', component: ContractSettingComponent}
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ContractRoutingModule { }
