<?php


namespace App\Controllers\Api;


use App\Models\Domain\DomainGroup;
use App\Models\Domain\DomainUser;
use App\Models\View\CardPrinter;
use System\App\AppException;
use System\Lang;
use System\Post;
use System\Traits\DomainTools;

class Groups extends APIController
{
    use DomainTools;

    public function getMemberListPost()
    {
        $time = time();
        $page = ((Post::get('page')) - 1);
        $groupName = Post::get("groupName");
        $pageSize = 50;
        $chunks = 5;


        $this->startBuffer();
        $group = new DomainGroup($groupName);
        /** @var DomainGroup $child
         * foreach ($groupChildren as $child) {
         * $this->sendBuffer('<div class="row">'
         * . '<div class="col"><a href="/groups/search/' . $child->activeDirectory->getAccountName() . '">' . $child->activeDirectory->getName() . '</a></div>'
         * . '<div class="col">' . $child->activeDirectory->getDescription() . '</div>'
         * . '<div class="col">' . CardPrinter::buildRemoveFromGroupButton($child, $group) . '</div>'
         * . '</div>');
         * }
         * }
         */
        //$groupMembers = $group->getMembers();

        $count = $group->countMembers();


        $pages = (int)($count / $pageSize);

        if ($count < $pageSize) {
            $pages++;
        } else if ($count % $pageSize > 0) {
            $pages++;
        }
        $pagination = "<div><div class='pr-2 clickable text-primary d-inline-block' onclick='getMemberPage(" . ($page) . ");'>Back</div>";
        for ($x = 1; $x <= $pages; $x++) {
            if ($x == $page + 1) {
                $pagination .= "<div class='px-1 d-inline-block '><u>" . $x . "</u></div>";
            } else {
                $pagination .= "<div class='px-1 d-inline-block clickable text-primary' onclick='getMemberPage(" . $x . ")'>" . $x . "</div>";
            }
        }
        $pagination .= "<div class='pl-2 clickable text-primary d-inline-block' onclick='getMemberPage(" . ($page + 2) . ");'>Next</div>";
        if ($pages > 1)
            $this->sendBuffer($pagination);


        //var_dump("count");
        //var_dump($count);
        //var_dump("pages");
        //var_dump($pages);
        //var_dump("page");
        //var_dump($page + 1);
        //var_dump("x");
        $x = $page * $chunks;
        //var_dump($x);
        //var_dump("Go To");
        $goto = $x + $chunks;
        $goto--;
        //var_dump($goto);
        for ($x; $x <= $goto; $x++) {
            //var_dump($x);
            $pageOfMember = $group->getPaginatedMembers(($pageSize / $chunks) - 1, $x);
            //var_dump($pageOfMember);
            foreach ($pageOfMember as $member) {
                try {
                    $user = new DomainUser($member);


                    $this->sendBuffer('<div class="row">'
                        . '<div class="col"><a href="/users/search/' . $user->getUsername() . '">' . $user->getUsername() . '</a></div>'
                        . '<div class="col">' . $user->getDisplayName() . '</div>'
                        . '<div class="col">' . CardPrinter::buildRemoveFromGroupButton($user, $group) . '</div>'
                        . '</div>');

                } catch (AppException $e) {

                }

            }

        }
        if ($pages > 1)
            $this->sendBuffer($pagination);


        $this->sendBuffer("</div>");

        exit();


    }

    public function newgetMemberListPost()
    {
        $time = time();
        $page = ((Post::get('page')) - 1);
        $groupName = Post::get("groupName");
        $pageSize = 50;
        $chunks = 5;


        //$this->startBuffer();
        $group = new DomainGroup($groupName);
        /** @var DomainGroup $child
         * foreach ($groupChildren as $child) {
         * $this->sendBuffer('<div class="row">'
         * . '<div class="col"><a href="/groups/search/' . $child->activeDirectory->getAccountName() . '">' . $child->activeDirectory->getName() . '</a></div>'
         * . '<div class="col">' . $child->activeDirectory->getDescription() . '</div>'
         * . '<div class="col">' . CardPrinter::buildRemoveFromGroupButton($child, $group) . '</div>'
         * . '</div>');
         * }
         * }
         */
        //$groupMembers = $group->getMembers();
        $output = '';
        $output .= (time() - $time);
        $count = $group->countMembers();
        $output .= (time() - $time);

        $pages = (int)($count / $pageSize);

        if ($count < $pageSize) {
            $pages++;
        } else if ($count % $pageSize > 0) {
            $pages++;
        }
        $pagination = "<div><div class='pr-2 clickable text-primary d-inline-block' onclick='getMemberPage(" . ($page) . ");'>Back</div>";
        for ($x = 1; $x <= $pages; $x++) {
            if ($x == $page + 1) {
                $pagination .= "<div class='px-1 d-inline-block '><u>" . $x . "</u></div>";
            } else {
                $pagination .= "<div class='px-1 d-inline-block clickable text-primary' onclick='getMemberPage(" . $x . ")'>" . $x . "</div>";
            }
        }
        $pagination .= "<div class='pl-2 clickable text-primary d-inline-block' onclick='getMemberPage(" . ($page + 2) . ");'>Next</div>";
        if ($pages > 1)
            $output .= ($pagination);
        $output .= (time() - $time);

        //var_dump("count");
        //var_dump($count);
        //var_dump("pages");
        //var_dump($pages);
        //var_dump("page");
        //var_dump($page + 1);
        //var_dump("x");
        $x = $page * $chunks;
        //var_dump($x);
        //var_dump("Go To");
        $goto = $x + $chunks;
        $goto--;
        //var_dump($goto);
        for ($x; $x <= $goto; $x++) {
            //var_dump($x);
            $pageOfMember = $group->getPaginatedMembers(($pageSize / $chunks) - 1, $x);
            //var_dump($pageOfMember);
            foreach ($pageOfMember as $member) {
                try {
                    $user = new DomainUser($member);
                    $output .= (time() - $time);

                    $output .= ('<div class="row">'
                        . '<div class="col"><a href="/users/search/' . $user->getUsername() . '">' . $user->getUsername() . '</a></div>'
                        . '<div class="col">' . $user->getDisplayName() . '</div>'
                        . '<div class="col">' . CardPrinter::buildRemoveFromGroupButton($user, $group) . '</div>'
                        . '</div>');

                } catch (AppException $e) {

                }

            }

        }
        if ($pages > 1)
            $output .= ($pagination);
        $output .= (time() - $time);

        $output .= ("</div>");
        echo $output;
        exit();


    }


}