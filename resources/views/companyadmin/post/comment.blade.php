<?php
//dd($commentReply);
        ?>
        <div class="form-group row cmry" id="{{$srno}}"><div class="col-md-12">
                <span style="float:left;">
                    <?php if ($commentReply['is_anonymous'] == 0) { ?>
                        <b><?php echo $commentReply['comment_reply_user']['name']; ?></b>
                        <?php
                    } else {
                        echo "<b>Anonymous</b>";
                    }
                    ?>

                    <br>
                    <small><?php echo " - on " . date('d/m/Y', strtotime($commentReply['created_at'])); ?></small>
                </span>  <br>
                <div class="col-md-12">    
                    <?php echo $commentReply['comment_reply']; ?></div>
            </div>  
        <?php if ($commentReply['user_id'] == Auth::user()->id) { ?>
            <span style="float:right;">
                <a href="{{url('/deletecommentReply',$commentReply['id'])}}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
            </span><?php } ?></div>
   