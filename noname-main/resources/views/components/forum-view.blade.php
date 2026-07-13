                <tr>
                    <td class="text-left">
                        <a href="/app/forum/view/{{ $forumPostId }}">{{ $forumPostName }}</a>
                        <p class="text-muted mb-0 fw-regular">{{ $forumPostReplies }} replies</p>
                    </td>
                    <td class="text-right">
                        <a href="/app/user/{{ $postCreatorId }}">{{ $postCreatorName }}</a>
                        <p class="text-muted mb-0 fw-regular">{{ $forumPostCreated }}</p>
                    </td>
                </tr>