<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function createTicket(Request $request, $slug)
    {
        //lấy thông tin nhà tổ chức;
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');

        $infor_event = DB::table('events')
            ->where([
                ['events.slug', '=', $slug],
                ['events.organizer_id', '=', $currentUser->id]
            ])
            ->first();

        return view('ticket.create', [
            'currentUser' => $currentUser,
            'infor_event' => $infor_event,
            'error' => [
                'name' => '',
                'cost' => '',
                'specialValidity' =>  ''
            ],
            'data' => [
                'name' => '',
                'cost' => 0,
                'type_specialValidity' => 'Không'
            ]
        ]);
    }

    public function handleCreateTicket(Request $request, $slug)
    {
        //lấy thông tin nhà tổ chức;
        $currentUser = json_decode($request->cookie('currentUser'));
        if (!$currentUser) return redirect('/login');
        //thông tin sự kiện
        $infor_event = DB::table('events')
            ->where([
                ['events.slug', '=', $slug],
                ['events.organizer_id', '=', $currentUser->id]
            ])
            ->first();

        $name_ticket = trim($request->input('name'));
        $cost_ticket = $request->input('cost');
        $type_specialValidity = $request->input('special_validity');
        $limit_amount = $request->input('limit-amount');
        $limit_time = $request->input('limit-time');
        $valueSpeacialValidity = null;

        $error_name = '';
        $error_cost = '';
        $error_specialValidity = '';

        //kiểm tra các giá trị có hợp lệ không;
        if (!$name_ticket) $error_name = 'Tên vé không được để trống!';
        if (!$cost_ticket || $cost_ticket <= 0)
            $error_cost = 'Giá vé không được đế trống và giá vé phải lớn hơn 0!';
        if ($type_specialValidity == 'amount'  && (!$limit_amount || $limit_amount <= 0)) {
            $error_specialValidity = 'Số lượng vé không được để trống và phải lớn hơn 0!';
        } elseif (
            $type_specialValidity == 'date' &&
            (!$limit_time || strtotime($limit_time) > strtotime($infor_event->date))
        ) {
            $error_specialValidity =
                'Ngày mua vé không được để trống và ngày mua phải trước ngày diễn ra sự kiện!';
        }
        if ($error_name || $error_cost || $error_specialValidity) {
            return view('ticket.create', [
                'currentUser' => $currentUser,
                'infor_event' => $infor_event,
                'error' => [
                    'name' => $error_name,
                    'cost' => $error_cost,
                    'specialValidity' =>  $error_specialValidity
                ],
                'data' => [
                    'name' => $name_ticket,
                    'cost' => $cost_ticket,
                    'type_specialValidity' => $type_specialValidity
                ]
            ]);
        }

        //xác định loại giá trị hiệu lực đặc biệt của vé;
        if ($limit_amount) {
            $valueSpeacialValidity = json_encode([
                'type' => $type_specialValidity,
                'amount' => $limit_amount
            ]);
        }
        if ($limit_time) {
            $valueSpeacialValidity = json_encode([
                'type' => $type_specialValidity,
                'date' => $limit_time
            ]);
        }

        //Lưu vé vào trong DB;
        $insert_ticket = DB::table('event_tickets')
            ->insert([
                'event_id' => $infor_event->id,
                'name' => $name_ticket,
                'cost' => $cost_ticket,
                'special_validity' => $valueSpeacialValidity
            ]);

        if ($insert_ticket)
            return redirect('/event/detail/' . $infor_event->slug);
        else {
            return view('ticket.create', [
                'currentUser' => $currentUser,
                'infor_event' => $infor_event,
                'error' => [
                    'name' => $error_name,
                    'cost' => $error_cost,
                    'specialValidity' =>  $error_specialValidity
                ],
                'data' => [
                    'name' => $name_ticket,
                    'cost' => $cost_ticket,
                    'type_specialValidity' => $type_specialValidity
                ]
            ]);
        }
    }
}
