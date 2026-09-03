<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * 미리보기용 샘플 데이터.
 *   php spark db:seed DemoSeeder
 *
 * 화면이 어떻게 보이는지 확인하기 위한 것이며 실제 내용이 아니다.
 * 제목에 모두 [샘플] 을 붙여 두었으므로, 오픈 전에 이 데이터는 지운다.
 */
class DemoSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $articles = [
            ['[샘플] 2026년 하계 휴무 안내', "당사는 아래와 같이 하계 휴무를 시행합니다.\n\n· 기간 : 2026년 8월 3일(월) ~ 8월 7일(금)\n· 문의 : 055-736-5959\n\n휴무 기간 중 접수된 문의는 업무 개시 후 순차적으로 답변드립니다.", 1, 5],
            ['[샘플] FPSO 통합 시운전 프로젝트 수주', "당사가 국내 조선소 FPSO 통합 시운전 프로젝트를 수주하였습니다.\n전기·계장 전 공정을 단일 책임체계로 수행합니다.", 0, 8],
            ['[샘플] 울산 지사 확장 이전 안내', "울산 지사가 확장 이전하였습니다.\n\n· 주소 : 울산 동구 방어진순환도로 400", 0, 3],
        ];
        foreach ($articles as [$title, $content, $is_notice, $days_ago]) {
            $this->db->table('articles')->insert([
                'title'      => $title,
                'content'    => $content,
                'is_notice'  => $is_notice,
                'view_count' => 0,
                'created_at' => date('Y-m-d H:i:s', strtotime('-' . $days_ago . ' days')),
                'updated_at' => $now,
            ]);
        }

        $recruits = [
            ['[샘플] 전기 시운전 엔지니어 채용', '정규직', "· 담당 업무 : 발전기·변압기·배전반 시험 및 계통 연계\n· 자격 요건 : 관련 경력 3년 이상\n· 근무지 : 경남 거제 (프로젝트에 따라 이동)", '2026-09-01', '2026-09-30', 1],
            ['[샘플] 계장 시운전 엔지니어 (경력)', '계약직', "· 담당 업무 : 계측기기 교정, DCS/ESD 로직 점검\n· 근무지 : 전남 영암", '2026-08-20', null, 1],
        ];
        foreach ($recruits as [$title, $type, $content, $starts, $ends, $open]) {
            $this->db->table('recruits')->insert([
                'title'           => $title,
                'employment_type' => $type,
                'content'         => $content,
                'starts_at'       => $starts,
                'ends_at'         => $ends,
                'is_open'         => $open,
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);
        }

        $inquiries = [
            ['견적 문의', '(주)한국해양기술', '김도현', '010-1234-5678', 'sample1@example.com', "FPSO 전기 시운전 견적을 요청드립니다.\n일정과 범위는 첨부 자료 기준으로 검토 부탁드립니다.", 0, 0],
            ['협업 제안', '대한플랜트', '이수진', '051-000-0000', 'sample2@example.com', '해외 프로젝트 관련 협업을 제안드리고자 합니다. 담당자 연결 부탁드립니다.', 0, 1],
            ['일반 문의', null, '박민호', null, 'sample3@example.com', '채용 관련해서 문의드립니다. 상시 지원도 가능한가요?', 1, 3],
        ];
        foreach ($inquiries as [$purpose, $company, $name, $phone, $email, $message, $is_read, $days_ago]) {
            $this->db->table('inquiries')->insert([
                'purpose'    => $purpose,
                'company'    => $company,
                'name'       => $name,
                'phone'      => $phone,
                'email'      => $email,
                'message'    => $message,
                'is_read'    => $is_read,
                'created_at' => date('Y-m-d H:i:s', strtotime('-' . $days_ago . ' days')),
                'updated_at' => $now,
            ]);
        }

        echo '샘플 데이터를 넣었습니다. 오픈 전에 [샘플] 글과 이 데이터를 지우세요.' . PHP_EOL;
    }
}
