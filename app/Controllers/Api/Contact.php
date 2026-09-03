<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\InquiryModel;
use App\Models\SettingModel;
use Throwable;

/**
 * 홈페이지 고객문의 접수.
 *
 * 지금까지는 방문자의 메일 프로그램을 여는 방식이라, 웹메일을 쓰는 사람에게는
 * 아무 반응이 없어 보였고 문의를 놓칠 수 있었다.
 * 이제 서버가 직접 받아 저장하고 담당자에게 메일을 보낸다.
 */
class Contact extends BaseController
{
    /** 같은 곳에서 1시간에 보낼 수 있는 문의 수 */
    private const MAX_PER_HOUR = 5;

    public function submit()
    {
        // 자동 발송 도배를 막는다.
        if (! service('throttler')->check(md5('contact-' . $this->request->getIPAddress()), self::MAX_PER_HOUR, HOUR)) {
            return $this->fail('잠시 후 다시 시도해 주세요. 짧은 시간에 여러 번 접수되었습니다.', 429);
        }

        // 사람이 아니면 채우고 마는 빈 칸(화면에서는 보이지 않는다).
        if (trim((string) $this->request->getPost('website')) !== '') {
            // 자동 발송으로 보고 조용히 성공한 것처럼 응답한다.
            return $this->ok();
        }

        if (! $this->request->getPost('privacy_agree')) {
            return $this->fail('개인정보 수집·이용에 동의해 주세요.');
        }

        $model = new InquiryModel();
        $data  = [
            'purpose' => trim((string) $this->request->getPost('purpose')),
            'company' => trim((string) $this->request->getPost('company')) ?: null,
            'name'    => trim((string) $this->request->getPost('name')),
            'phone'   => trim((string) $this->request->getPost('phone')) ?: null,
            'email'   => trim((string) $this->request->getPost('email')),
            'message' => trim((string) $this->request->getPost('message')),
        ];

        if (! $model->insert($data)) {
            // 어느 칸이 문제인지 첫 번째 것만 알려 준다.
            return $this->fail(implode(' ', $model->errors()));
        }

        $this->notify($data);

        return $this->ok();
    }

    /**
     * 담당자에게 알림 메일을 보낸다.
     * 메일이 실패해도 문의는 이미 저장되어 있으므로 접수 자체는 성공으로 본다.
     */
    private function notify(array $data): void
    {
        $to = (new SettingModel())->all()['email'] ?? '';
        if ($to === '') {
            return;
        }

        $body = "홈페이지에서 문의가 접수되었습니다.\n\n"
            . '문의 목적 : ' . $data['purpose'] . "\n"
            . '회사/기관 : ' . ($data['company'] ?: '-') . "\n"
            . '이름      : ' . $data['name'] . "\n"
            . '연락처    : ' . ($data['phone'] ?: '-') . "\n"
            . '이메일    : ' . $data['email'] . "\n"
            . "\n내용\n" . str_repeat('-', 40) . "\n" . $data['message'] . "\n"
            . str_repeat('-', 40) . "\n\n"
            . '관리자에서 보기 : ' . base_url('admin/inquiry');

        try {
            $email = service('email');
            $email->setTo($to);
            $email->setReplyTo($data['email'], $data['name']);   // 받은 메일에서 바로 답장되도록
            $email->setSubject('[홈페이지 문의] ' . $data['purpose'] . ' — ' . $data['name']);
            $email->setMessage($body);
            $email->send();
        } catch (Throwable $e) {
            log_message('error', '문의 알림 메일 실패: ' . $e->getMessage());
        }
    }

    private function ok()
    {
        return $this->response->setJSON([
            'status'  => true,
            'message' => '문의가 접수되었습니다. 담당자가 확인 후 연락드리겠습니다.',
            'data'    => $this->freshToken(),
        ]);
    }

    private function fail(string $message, int $code = 400)
    {
        return $this->response->setStatusCode($code)->setJSON([
            'status'  => false,
            'message' => $message,
            'data'    => $this->freshToken(),
        ]);
    }

    /**
     * 보안 토큰은 요청마다 새로 발급된다.
     * 화면을 새로 고치지 않으므로, 다음 전송에 쓸 토큰을 응답에 같이 실어 보낸다.
     */
    private function freshToken(): array
    {
        return ['csrf_name' => csrf_token(), 'csrf_hash' => csrf_hash()];
    }
}
