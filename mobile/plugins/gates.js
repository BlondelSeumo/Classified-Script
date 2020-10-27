import Vue from 'vue'
import ModeratorGate from '~/gates/ModeratorGate'
import MemberGate from '~/gates/MemberGate'
import AdministratorGate from '~/gates/AdministratorGate'
import OwnerGate from '~/gates/OwnerGate'

export default function (ctx, inject) {
	inject('mogate', new ModeratorGate())
	inject('megate', new MemberGate())
	inject('adgate', new AdministratorGate())
	inject('owgate', new OwnerGate())
}