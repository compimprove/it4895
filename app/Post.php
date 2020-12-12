<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Post extends Model
{
    //
    protected $table = 'posts';
    protected $fillable = [
        'id', 'user_id', 'described', 'like'
    ];
    public static $sampleParagragh = [
        '(y) (y) (y) (y)
        Vì công việc của một chuyên gia Công nghệ thông tin đã khiến tôi có lối sống ít vận động, nên tôi đã bắt đầu tập chạy cách đây 5 năm với mục đích giữ cho bản thân mình khỏe mạnh. Nhưng tôi thường chăm chỉ trong một vài ngày, sau đó lại nghỉ tập và sẽ kéo dài thành một thời gian nghỉ rất dài. Tôi nhận ra rằng, để có được bất kỳ thành tựu thực sự nào, chúng ta phải nhất quán. Chính vì vậy, tôi đã đăng ký thử thách 100 ngày tập chạy để ép mình nhất quán. Tôi chạy đều đặn mỗi ngày trong tuần, chỉ nghỉ giải lao vào cuối tuần.',
        'Hành trình từ Ngày 1 đến Ngày 50. (y) (y) (y) (y)
        Tôi đã chạy lần đầu tiên vào ngày 20 tháng 7. Sau một vài ngày, tôi nhận thấy nhiều thay đổi đã xảy ra trong lối sống của mình. Chạy bộ mỗi sáng đã tạo ra một lịch trình rất cần thiết cho cuộc sống của tôi. Không chỉ vậy, tôi có thời gian lên kế hoạch tốt trong ngày, ăn ngon miệng, ngủ ngon và có một cái nhìn tích cực hơn trước. Tôi đọc thêm sách, viết nhiều hơn và cũng dành nhiều thời gian hơn cho gia đình.',
        'Từ khoảng ngày thứ 60 đến ngày thứ 70, tôi bị đau bụng. Cả bác sĩ và tôi đều không biết chắc việc tập chạy có phải nguyên nhân của những cơn đau này hay không. Bác sĩ nói tôi vẫn có thể chạy thêm vài ngày nữa, nhưng tôi không thấy thoải mái nên đã dừng lại ở 70 ngày. <3 <3 <3
        Tôi nói điều này không phải để làm nản lòng bất cứ ai, nhưng hãy nhớ trong cuộc sống, một số điều không thể đoán trước được. Điều đó đã dạy tôi nhìn cuộc sống một cách sâu sắc hơn rất nhiều.',
        'Trong suốt thời gian đó, tôi cảm thấy tuyệt vời cả về thể chất và tinh thần. Nếu không có thử thách này, tôi không biết mình sẽ gặp vấn đề gì khác khi ngồi ở nhà với tình trạng COVID này.
        Tuy nhiên, cơn đau phát triển khiến tôi nghĩ nhiều hơn đến việc tập thể dục vừa phải, đi bộ, chạy có thể 2-3 lần một tuần. Tôi cảm thấy tốt hơn là nên nghỉ ngơi thường xuyên hơn là nghỉ vào cuối tuần. 8| 8| 8| 8|',
        '-_- -_- -_- -_-
        Tôi học được tầm quan trọng của việc sống chậm lại và trân trọng những điều nhỏ nhặt. Chạy, giống như viết, là một liệu pháp. Đôi khi, chúng ta chạy theo cuộc sống quá nhiều, theo đuổi những ước mơ và mục tiêu mà quên tận hưởng những điều nhỏ bé. 8| 8| 8|',
        'Sau mỗi buổi chạy, tôi nghỉ ngơi một lúc trong không gian thiên nhiên và thời gian này thực sự giúp tôi sống chậm lại và giữ vững lập trường. Những ngày cuối cùng khi tôi phát hiện những cơn đau của mình, tôi nhìn lại cuộc sống nhiều hơn. Tôi sống chậm lại và tạm dừng nhiều hoạt động để tận hưởng những điều nhỏ bé trong cuộc sống của mình.:D :D :D :D :D',
        ':):):):):):):):):)
        Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exercitation ulliam corper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem veleum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel willum lunombro dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.'
    ];

    public function images()
    {
        return $this->hasMany('App\Image', 'post_id', 'id');
    }

    public function videos()
    {
        return $this->hasMany('App\Video', 'post_id', 'id');
    }
}


